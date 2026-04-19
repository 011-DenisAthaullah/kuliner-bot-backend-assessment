<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        $r = Restaurant::firstOrCreate(
            ['name' => 'Warung Padang Enak'],
            [
                'address' => 'Jakarta',
                'lat' => -6.2,
                'lng' => 106.8
            ]
        );

        Menu::firstOrCreate([
            'restaurant_id' => $r->id,
            'name' => 'Rendang'
        ],[
            'price' => 25000
        ]);

        Review::firstOrCreate([
            'restaurant_id' => $r->id,
            'user_id' => $user->id,
        ],[
            'rating' => 5,
            'review' => 'Enak banget'
        ]);
    }
}