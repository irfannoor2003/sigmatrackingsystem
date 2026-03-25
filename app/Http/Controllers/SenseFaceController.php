<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class SenseFaceController extends Controller
{
    public function receive(Request $request)
    {
        // Optional: validate token header for security
        $token = $request->header('X-SenseFace-Token');
        if ($token !== env('SENSEFACE_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->all();

        if (!is_array($data)) {
            return response()->json(['error' => 'Invalid format, expected JSON array'], 400);
        }

        $count = 0;

        foreach ($data as $log) {
            $userId = $log['user_id'] ?? null;
            $time   = $log['time'] ?? null;

            if (!$userId || !$time) continue;

            $date = substr($time, 0, 10); // YYYY-MM-DD

            // Skip if already exists
            if (Attendance::where('salesman_id', $userId)->where('date', $date)->exists()) {
                continue;
            }

            Attendance::create([
                'salesman_id'     => $userId,
                'date'            => $date,
                'status'          => 'present',
                'clock_in'        => $time,
                'checkin_method'  => 'face',
                'office_verified' => true,
            ]);

            $count++;
        }

        return response()->json(['success' => true, 'synced' => $count]);
    }
}
