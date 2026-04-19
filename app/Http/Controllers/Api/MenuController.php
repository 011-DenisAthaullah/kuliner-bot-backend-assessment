<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\MenuRepositoryInterface;

class MenuController extends Controller {

    public function __construct(
        protected MenuRepositoryInterface $repo
    ) {}

    public function index() {
        return $this->repo->all();
    }

    public function store(Request $r) {
        return $this->repo->create($r->all());
    }

    public function show($id) {
        return $this->repo->find($id);
    }

    public function update(Request $r, $id) {
        return $this->repo->update($id, $r->all());
    }

    public function destroy($id) {
        return $this->repo->delete($id);
    }
}