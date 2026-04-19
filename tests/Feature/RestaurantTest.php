<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_restaurant()
    {
        // 1. buat user dummy
        $user = User::factory()->create([
            'email' => 'test@user.com',
            'password' => bcrypt('password')
        ]);

        // 2. login ambil token JWT
        $token = JWTAuth::fromUser($user);

        // 3. request pakai token
        $res = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/restaurants/search?q=jakarta');

        // 4. validasi minimal
        $res->assertStatus(200);
    }
}