<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use Illuminate\Http\Request;

class LogApiController extends Controller
{
    public function index()
    {
        $logs = ApiLog::latest()->get();

        return response()->json([
            'data' => $logs
        ]);
    }
}
