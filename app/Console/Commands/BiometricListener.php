<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\AttendanceService;
use App\Models\User; // Import the User model to verify IDs
use Exception;

class BiometricListener extends Command
{
    protected $signature = 'biometric:listen';
    protected $description = 'Listen for Feteck scanner and update the attendances table.';

    public function handle(AttendanceService $service)
    {
        $port = 5055;
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        if (!$socket) {
            $this->error("Failed to create socket.");
            return;
        }

        socket_bind($socket, '0.0.0.0', $port);

        $this->info("Listening for Fingerprints (Ids) on port $port...");

        while (true) {
            socket_recvfrom($socket, $buf, 1024, 0, $from, $port_received);
            $bytes = unpack("C*", $buf);

            // 1. Get the ID from Byte 9 (Standard)
            $userId = $bytes[9] ?? 0;

            // 2. If Byte 9 is 204 (cc), it's just a status heartbeat. Skip it.
            if ($userId == 204) {
                continue;
            }

            // 3. Process the actual scan
            if ($userId > 0) {
                $cacheKey = "bio_scan_{$userId}";

                if (!Cache::has($cacheKey)) {

                    // --- FIX: Check if user exists before processing ---
                    $userExists = User::where('id', $userId)->exists();

                    if (!$userExists) {
                        $this->warn("✕ Unknown Scan: ID $userId does not exist in the database.");

                        // Prevent the console from flooding if they hold their finger down
                        Cache::put($cacheKey, true, 5);
                        continue;
                    }

                    // --- FIX: Wrap in try-catch to avoid script crashing ---
                    try {
                        $result = $service->processScan($userId, $from);

                        if ($result['status'] === 'success') {
                            $this->info("✓ User $userId: " . $result['type']);
                        } else {
                            $this->error("✕ User $userId: " . $result['message']);
                        }
                    } catch (\Exception $e) {
                        // Catching the exception keeps the 'while' loop alive
                        $this->error("✕ Database Error for User $userId: " . $e->getMessage());
                    }

                    // Set 20-second cooldown for successful/processed scans
                    Cache::put($cacheKey, true, 20);
                }
            }
        }
    }
}
