<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class TelegramTest extends TestCase
{
    public function test_webhook_text()
    {
        Http::fake([
            '*' => Http::response(['ok' => true], 200),
        ]);

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