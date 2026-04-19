<?php

namespace App\Repositories;

use App\Models\Restaurant;
use App\Repositories\Interfaces\RestaurantRepositoryInterface;

class RestaurantRepository implements RestaurantRepositoryInterface
{
    public function all()
    {
        return Restaurant::with(['menus','reviews'])->get();
    }

    public function find($id)
    {
        return Restaurant::with(['menus','reviews'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Restaurant::create($data);
    }

    public function update($id, array $data)
    {
        $r = Restaurant::findOrFail($id);
        $r->update($data);
        return $r;
    }

    public function delete($id)
    {
        return Restaurant::destroy($id);
    }
}