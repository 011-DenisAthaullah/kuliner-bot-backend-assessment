<?php

namespace Tests\Feature;

use Tests\TestCase;

class TelegramTest extends TestCase
{
    public function test_webhook_text()
    {
        $res = $this->postJson('/api/telegram/webhook', [
            "message" => [
                "chat" => ["id" => 123],
                "text" => "/search jakarta"
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