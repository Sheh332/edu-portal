<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Sends WhatsApp notifications through a configurable driver.
 *
 * Triggers required by the spec:
 *   - attendance marked
 *   - leave request submitted
 *   - task assigned
 *   - task approved / rejected
 *
 * The default "log" driver records every message to the Laravel log so the
 * feature is fully wired even without live API credentials. Configure the
 * "meta" or "twilio" driver in config/whatsapp.php to send real messages.
 */
class WhatsAppService
{
    public function send(?string $phone, string $message): bool
    {
        if (empty($phone)) {
            Log::warning('WhatsApp: no phone number for message', ['message' => $message]);
            return false;
        }

        return match (config('whatsapp.driver')) {
            'meta' => $this->sendViaMeta($phone, $message),
            'twilio' => $this->sendViaTwilio($phone, $message),
            default => $this->sendViaLog($phone, $message),
        };
    }

    public function sendToUser(?User $user, string $message): bool
    {
        return $this->send($user?->phone, $message);
    }

    protected function sendViaLog(string $phone, string $message): bool
    {
        Log::info("[WhatsApp → {$phone}] {$message}");
        return true;
    }

    protected function sendViaMeta(string $phone, string $message): bool
    {
        $cfg = config('whatsapp.meta');
        $url = "https://graph.facebook.com/{$cfg['api_version']}/{$cfg['phone_number_id']}/messages";

        try {
            $res = Http::withToken($cfg['token'])->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $this->normalize($phone),
                'type' => 'text',
                'text' => ['body' => $message],
            ]);

            if ($res->failed()) {
                Log::error('WhatsApp Meta send failed', ['body' => $res->body()]);
            }

            return $res->successful();
        } catch (\Throwable $e) {
            Log::error('WhatsApp Meta exception: '.$e->getMessage());
            return false;
        }
    }

    protected function sendViaTwilio(string $phone, string $message): bool
    {
        $cfg = config('whatsapp.twilio');
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$cfg['sid']}/Messages.json";

        try {
            $res = Http::asForm()->withBasicAuth($cfg['sid'], $cfg['token'])->post($url, [
                'From' => $cfg['from'],
                'To' => 'whatsapp:'.$this->normalize($phone),
                'Body' => $message,
            ]);

            if ($res->failed()) {
                Log::error('WhatsApp Twilio send failed', ['body' => $res->body()]);
            }

            return $res->successful();
        } catch (\Throwable $e) {
            Log::error('WhatsApp Twilio exception: '.$e->getMessage());
            return false;
        }
    }

    protected function normalize(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone);
    }
}
