<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect if not logged in
        }

        $userRole = Auth::user()->role_id; // Assuming `role_id` is stored in `users` table

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized action.'); // Restrict access
        }

        return $next($request);
    }
}
