<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage($chatId, $text)
    {
        $baseUrl = config('services.telegram.base_url');
        $token   = config('services.telegram.token');

        if (!$baseUrl || !$token) {
            throw new \Exception("Telegram config missing");
        }


        $url = "{$baseUrl}/bot{$token}/sendMessage";

        return Http::post($url, [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}