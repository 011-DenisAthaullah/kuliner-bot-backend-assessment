<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RestaurantService
{
    protected string $baseUrl = 'https://travel-advisor.p.rapidapi.com';

    public function search(string $query)
    {
        // STEP 1: cari location_id dari text
        $location = Http::withHeaders([
            'X-RapidAPI-Key'  => config('services.rapidapi.key'),
            'X-RapidAPI-Host' => config('services.rapidapi.host'),
        ])->get($this->baseUrl . '/locations/search', [
            'query' => $query,
            'limit' => 1,
        ]);

        if ($location->failed()) {
            return ['error' => 'failed get location'];
        }

        $locationId = $location->json()['data'][0]['result_object']['location_id'] ?? null;

        if (!$locationId) {
            return ['error' => 'location not found'];
        }

        // STEP 2: ambil restoran dari location_id
        $restaurants = Http::withHeaders([
            'X-RapidAPI-Key'  => config('services.rapidapi.key'),
            'X-RapidAPI-Host' => config('services.rapidapi.host'),
        ])->get($this->baseUrl . '/restaurants/list', [
            'location_id' => $locationId,
            'limit' => 10,
        ]);

        if ($restaurants->failed()) {
            return ['error' => 'failed get restaurants'];
        }

        // STEP 3: mapping data biar ga berantakan
        return collect($restaurants->json()['data'])
            ->map(function ($item) {
                return [
                    'name' => $item['name'] ?? null,
                    'address' => $item['address'] ?? null,
                    'rating' => $item['rating'] ?? null,
                ];
            })
            ->filter()
            ->values();
    }
}