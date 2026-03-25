<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Attendance; // Make sure this model exists!

class AdmsController extends Controller
{
    /**
     * Device Handshake / Heartbeat
     * GET /iclock/getrequest?SN=XXXXXXXXXXXXX
     */
    public function handleGetRequest(Request $request)
    {
        $sn = $request->query('SN');

        // You can use this to track if the device is "Online" in your dashboard
        Log::info("Device Check-in: SN $sn");

        // Return 'OK' to tell the device the server is alive
        return response("OK");
    }

    /**
     * Data Upload (Attendance Logs)
     * POST /iclock/cdata?SN=XXXXXXXXXXXXX&table=ATTLOG
     */
    public function handleDataUpload(Request $request)
    {
        $content = $request->getContent();

        // Example Raw Data: 101	2026-03-18 15:30:00	0	0	0	0
        // (Columns: UserID, Timestamp, State, VerifyMode, etc.)

        $lines = explode("\n", trim($content));

        foreach ($lines as $line) {
            $data = explode("\t", $line); // ADMS often uses Tabs (\t)

            if (count($data) >= 2) {
                $employeeId = $data[0];
                $timestamp  = $data[1];

                // Save to your Database
                // Attendance::create([
                //    'employee_id' => $employeeId,
                //    'clock_in'    => $timestamp,
                //    'device_sn'   => $request->query('SN')
                // ]);

                Log::info("Attendance Captured: Emp $employeeId at $timestamp");
            }
        }

        return response("OK");
    }
}
