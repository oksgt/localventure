<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role_id;

        Log::info("RoleMiddleware Debug - User Role: {$userRole}, Allowed Roles: " . implode(',', $roles));

        // Ensure roles are correctly interpreted as an array
        $allowedRoles = array_map('trim', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            return response()->view('errors.403', [], 403);
        }

        return $next($request);
    }
}
