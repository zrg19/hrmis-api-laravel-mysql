<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if user has any of the required roles using Spatie Permission
        $hasRole = false;
        // \Log::info('CheckRole middleware - User: ' . $request->user()->id);
        // \Log::info('CheckRole middleware - Required roles: ' . implode(', ', $roles));
        // \Log::info('CheckRole middleware - User roles: ' . $request->user()->roles->pluck('name')->implode(', '));

        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                $hasRole = true;
                break;
            }
        }

        // \Log::info('CheckRole middleware - Access granted for user: ' . $request->user()->id);

        if (!$hasRole) {
            return response()->json(['error' => 'Forbidden - Insufficient permissions'], 403);
        }

        return $next($request);
    }
}
