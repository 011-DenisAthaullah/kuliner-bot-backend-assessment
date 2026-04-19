<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = ApiLog::latest()->get();
        return view('logs.index', compact('logs'));
    }
}
