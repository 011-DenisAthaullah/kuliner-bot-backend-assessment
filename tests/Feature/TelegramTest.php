<?php

namespace Tests\Feature;

use App\Services\TelegramService;
use App\Services\RestaurantService;
use Tests\TestCase;

class TelegramTest extends TestCase
{
    public function test_webhook_text()
    {
        $this->mock(TelegramService::class, function ($mock) {
            $mock->shouldReceive('sendMessage')
                ->once()
                ->andReturn(true);
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
        $this->mock(TelegramService::class, function ($mock) {
            $mock->shouldReceive('sendMessage')
                ->once()
                ->andReturn(true);
        });

        $this->mock(RestaurantService::class, function ($mock) {
            $mock->shouldReceive('search')
                ->once()
                ->andReturn([
                    [
                        'name' => 'Restoran A',
                        'address' => 'Jakarta',
                        'rating' => 4.5
                    ]
                ]);
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