<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\TelegramService;
use App\Services\RestaurantService;

class TelegramTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // reset binding biar gak ke-cache instance lama
        $this->app->forgetInstance(TelegramService::class);
        $this->app->forgetInstance(RestaurantService::class);

        // MOCK TELEGRAM SERVICE
        $this->app->bind(TelegramService::class, function () {
            return new class {
                public function sendMessage($chatId, $text)
                {
                    return true;
                }
            };
        });

        // MOCK RESTAURANT SERVICE
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
    }

    public function test_webhook_text()
    {
        $this->postJson('/api/telegram/webhook', [
            'message' => [
                'chat' => ['id' => 123],
                'text' => 'halo'
            ]
        ])->assertStatus(200);
    }

    public function test_webhook_location()
    {
        $res = $this->postJson('/api/telegram/webhook', [
            'message' => [
                'chat' => ['id' => 123],
                'location' => [
                    'latitude' => -6.2,
                    'longitude' => 106.8
                ]
            ]
        ]);

        $res->assertStatus(200);
    }
}