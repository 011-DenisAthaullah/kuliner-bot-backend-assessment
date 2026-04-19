<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage($chatId, $text)
    {
        if (app()->environment('testing')) {
            return true;
        }

        $url = config('services.telegram.base_url')
            . "/bot"
            . config('services.telegram.token')
            . "/sendMessage";

        return Http::post($url, [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}