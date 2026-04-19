<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\RestaurantRepositoryInterface;
use App\Services\RestaurantService;

class RestaurantController extends Controller
{
    protected $repo;
    protected $service;

    public function __construct(
        RestaurantRepositoryInterface $repo,
        RestaurantService $service
    ) {
        $this->repo = $repo;
        $this->service = $service;
    }

    // ======================
    // CRUD (DATABASE)
    // ======================

    public function index()
    {
        return response()->json($this->repo->all());
    }

    public function store(Request $request)
    {
        return response()->json(
            $this->repo->create($request->all())
        );
    }

    public function show(string $id)
    {
        return response()->json(
            $this->repo->find($id)
        );
    }

    public function update(Request $request, string $id)
    {
        return response()->json(
            $this->repo->update($id, $request->all())
        );
    }

    public function destroy(string $id)
    {
        return response()->json(
            $this->repo->delete($id)
        );
    }

    // ======================
    // SEARCH (API EXTERNAL)
    // ======================

    public function search(Request $request)
    {
        $q = $request->query('q');

        if (!$q) {
            return response()->json([
                'message' => 'query is required'
            ], 422);
        }

        $data = $this->service->search($q);

        return response()->json($data);
    }
}