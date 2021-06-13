<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        $userRoles = $user->roles;
        foreach ($roles as $key => $role) {
            foreach ($userRoles as $key => $userRole) {
                if ($userRole->name == $role) {
                    return $next($request);
                }
            }
        }
        return response()->json([
            'status' => 201,
            'message' => 'Unauthorized',
        ]);
    }
}
