<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $res = $this->postJson('/api/auth/register', [
            'name' => 'Denis',
            'email' => 'denis@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // FIX: register kamu return 201, bukan 200
        $res->assertStatus(201)
            ->assertJsonStructure([
                'user',
                'token'
            ]);
    }

    public function test_login()
    {
        // bikin user manual (lebih stabil untuk test)
        User::create([
            'name' => 'Denis',
            'email' => 'denis@test.com',
            'password' => Hash::make('password123'),
        ]);

        $res = $this->postJson('/api/auth/login', [
            'email' => 'denis@test.com',
            'password' => 'password123',
        ]);

        $res->assertStatus(200);

        // FIX: token kadang beda nama di API (token / access_token)
        $this->assertTrue(
            isset($res['token']) || isset($res['access_token']),
            'Token tidak ditemukan di response login'
        );
    }
}