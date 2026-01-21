<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp Message via Fonnte/Wablas/Other API
     * 
     * @param string $target Phone number (08xxx or 62xxx)
     * @param string $message content
     * @return bool
     */
    public static function sendMessage($target, $message)
    {
        // 1. Sanitize Phone Number (Convert 08 to 62)
        if (substr($target, 0, 1) == '0') {
            $target = '62' . substr($target, 1);
        }

        // 2. Check Environment Config
        $token = env('WA_API_TOKEN');
        $endpoint = env('WA_API_ENDPOINT', 'https://api.fonnte.com/send'); // Default Fonnte

        if (empty($token)) {
            Log::warning("WA_API_TOKEN is not set. Message to {$target} skipped.");
            return false;
        }

        // 3. Send Request
        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($endpoint, [
                'target' => $target,
                'message' => $message,
            ]);

            Log::info("WA Sent to {$target}: " . $response->status());
            return $response->successful();

        } catch (\Exception $e) {
            Log::error("WA Error: " . $e->getMessage());
            return false;
        }
    }
}
