<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Visit;

class NotificationService
{
    public static function sendWhatsApp(?string $to, string $message): bool
    {
        if (empty($to)) {
            Log::warning('WhatsApp: no recipient phone provided');
            return false;
        }

        // Normalize and validate recipient number
        // Accept values like "whatsapp:+123..." or plain numbers; strip spaces and formatting
        if (strpos($to, 'whatsapp:') === 0) {
            $to = substr($to, 9);
        }

        // Remove all characters except digits and leading plus
        $clean = preg_replace('/[^+0-9]/', '', $to);

        // Must be in E.164 format (start with +). If not, warn and abort — avoid Twilio 21211 errors
        if (strpos($clean, '+') !== 0) {
            Log::warning('WhatsApp: recipient phone not in E.164 format', ['original' => $to]);
            return false;
        }

        $to = 'whatsapp:'.$clean;

        $sid   = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from  = env('TWILIO_WHATSAPP_FROM');

        if (!$sid || !$token || !$from) {
            Log::info('WhatsApp not sent (Twilio not configured). Message: ' . $message);
            return false;
        }

        // Clean phone number
        $number = str_replace([' ', '-', '(', ')'], '', $to);

        // Remove whatsapp: if already present
        $number = str_replace('whatsapp:', '', $number);

        // Convert Pakistani local format
        if (preg_match('/^0\d+$/', $number)) {
            $number = '92' . substr($number, 1);
        }

        // Add + if missing
        if (!str_starts_with($number, '+')) {
            $number = '+' . $number;
        }

        $twilioTo = 'whatsapp:' . $number;

        Log::info('Sending WhatsApp', [
            'original' => $to,
            'formatted' => $twilioTo,
        ]);

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";

Log::info('WhatsApp Debug', [
    'original' => $to,
    'formatted' => $twilioTo,
]);

        try {
            $response = Http::asForm()
                ->withBasicAuth($sid, $token)
                ->post($url, [
                    'From' => $from,
                    'To'   => $twilioTo,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp sent successfully');
                return true;
            }

            Log::error('WhatsApp send failed', [
                'status' => $response->status(),
                'resp'   => $response->body(),
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('WhatsApp send exception', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public static function sendEmailVisitReminder(string $to, string $salesmanName, Visit $visit): bool
    {
        try {
            Mail::to($to)->send(new \App\Mail\VisitReminder($salesmanName, $visit));
            Log::info('Visit reminder email sent', ['to' => $to, 'visit_id' => $visit->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed sending visit reminder email', [
                'error' => $e->getMessage(),
                'to' => $to,
                'visit_id' => $visit->id,
            ]);
            return false;
        }
    }

    public static function sendEmailVisitBlocked(string $to, string $salesmanName, Visit $visit): bool
    {
        try {
            Mail::to($to)->send(new \App\Mail\VisitBlocked($salesmanName, $visit));
            Log::info('Visit blocked email sent', ['to' => $to, 'visit_id' => $visit->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed sending visit blocked email', [
                'error' => $e->getMessage(),
                'to' => $to,
                'visit_id' => $visit->id,
            ]);
            return false;
        }
    }

    public static function sendEmailVisitUnblocked(string $to, string $salesmanName, Visit $visit, string $adminName): bool
    {
        try {
            Mail::to($to)->send(new \App\Mail\VisitUnblocked($salesmanName, $visit, $adminName));
            Log::info('Visit unblocked email sent', ['to' => $to, 'visit_id' => $visit->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed sending visit unblocked email', [
                'error' => $e->getMessage(),
                'to' => $to,
                'visit_id' => $visit->id,
            ]);
            return false;
        }
    }
}
