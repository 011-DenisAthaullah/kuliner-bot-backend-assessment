<?php

namespace Tests\Feature;

use App\Services\TelegramService;
use App\Services\RestaurantService;
use Tests\TestCase;

class TelegramTest extends TestCase
{
    public function test_webhook_text()
    {
        $this->app->bind(TelegramService::class, function () {
            return new class {
                public function sendMessage($chatId, $text)
                {
                    return true;
                }
            };
        });

        $res = $this->postJson('/api/telegram/webhook', [
            'message' => [
                'chat' => ['id' => 123],
                'text' => 'halo'
            ]
        ]);

        $res->assertStatus(200);
    }

    public function test_webhook_location()
    {
        $this->app->bind(TelegramService::class, function () {
            return new class {
                public function sendMessage($chatId, $text)
                {
                    return true;
                }
            };
        });

        $this->app->bind(RestaurantService::class, function () {
            return new class {
                public function search($query)
                {
                    return [
                        [
                            'name' => 'Restoran A',
                            'address' => 'Jakarta',
                            'rating' => 4.5
                        ]
                    ];
                }
            };
        });

        $res = $this->postJson('/api/telegram/webhook', [
            "message" => [
                "chat" => ["id" => 123],
                "location" => [
                    "latitude" => -6.2,
                    "longitude" => 106.8
                ]
            ]
        ]);

        $res->assertStatus(200);
    }
}