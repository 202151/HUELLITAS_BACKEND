<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check() && $request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $request->method(),
                'model_type' => $this->getModelFromRoute($request),
                'description' => $this->generateDescription($request),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }

    private function getModelFromRoute(Request $request)
    {
        $route = $request->route();
        if ($route) {
            $routeName = $route->getName();
            if ($routeName) {
                $parts = explode('.', $routeName);
                return isset($parts[0]) ? ucfirst($parts[0]) : null;
            }
        }
        return null;
    }

    private function generateDescription(Request $request)
    {
        $method = $request->method();
        $path = $request->path();
        
        return "{$method} request to {$path}";
    }
}
