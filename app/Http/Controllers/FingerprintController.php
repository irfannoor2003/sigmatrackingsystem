<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class FingerprintController extends Controller
{
    /**
     * Receive attendance data from SenseFace device
     */
    public function receiveData(Request $request)
    {
        // Log incoming payload for testing
        \Log::info('Fingerprint Device Payload:', $request->all());

        // Device payload example: { "user_id": 101, "timestamp": "2026-03-18 10:15:00" }
        $deviceUserId = $request->input('user_id');
        $timestamp    = $request->input('timestamp', now());

        // Find the salesman mapped to this device_id
        $salesman = User::where('device_id', $deviceUserId)->first();

        if (!$salesman) {
            return response()->json(['error' => 'Salesman not found'], 404);
        }

        // Determine today’s date
        $today = Carbon::parse($timestamp)->format('Y-m-d');

        // Check if attendance already exists for today
        $attendance = Attendance::where('user_id', $salesman->id)
                        ->whereDate('clock_in', $today)
                        ->first();

        if (!$attendance) {
            // No record yet → Clock-in
            Attendance::create([
                'user_id' => $salesman->id,
                'clock_in' => $timestamp,
                'status' => 'present',
            ]);

            $action = 'clocked in';
        } else if (!$attendance->clock_out) {
            // Already clocked in, now clock-out
            $attendance->clock_out = $timestamp;
            $attendance->save();

            $action = 'clocked out';
        } else {
            $action = 'already completed attendance today';
        }

        return response()->json([
            'status' => 'ok',
            'action' => $action,
            'salesman' => $salesman->name,
            'time' => $timestamp
        ]);
    }
}
