<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Interfaces\ReviewRepositoryInterface;

class ReviewRepository implements ReviewRepositoryInterface {

    public function all() {
        return Review::all();
    }

    public function find($id) {
        return Review::findOrFail($id);
    }

    public function create(array $data) {
        return Review::create($data);
    }

    public function update($id, array $data) {
        $r = Review::findOrFail($id);
        $r->update($data);
        return $r;
    }

    public function delete($id) {
        return Review::destroy($id);
    }
}