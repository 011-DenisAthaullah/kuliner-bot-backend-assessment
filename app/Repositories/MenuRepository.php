<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Interfaces\MenuRepositoryInterface;

class MenuRepository implements MenuRepositoryInterface {

    public function all() {
        return Menu::all();
    }

    public function find($id) {
        return Menu::findOrFail($id);
    }

    public function create(array $data) {
        return Menu::create($data);
    }

    public function update($id, array $data) {
        $m = Menu::findOrFail($id);
        $m->update($data);
        return $m;
    }

    public function delete($id) {
        return Menu::destroy($id);
    }
}