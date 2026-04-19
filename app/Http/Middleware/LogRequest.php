<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Auth;

class LogRequest
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            \App\Models\ApiLog::create([
                'endpoint' => $request->path(),
                'method'   => $request->method(),
                'body'     => $request->all(),
                'headers'  => $request->headers->all(),
                'ip'       => $request->ip(),
                'user_id' => $request->user()?->id
            ]);
        } catch (\Throwable $e) {
            // jangan ganggu response utama
        }

        return $response;
    }
}
