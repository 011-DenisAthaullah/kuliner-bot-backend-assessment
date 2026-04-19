<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage($chatId, $text)
    {
        $url = config('services.telegram.api')
            . '/bot'
            . config('services.telegram.token')
            . '/sendMessage';

        return Http::post($url, [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}