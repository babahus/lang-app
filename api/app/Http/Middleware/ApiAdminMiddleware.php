<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ApiAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userRoleId = Cache::get('users_role_' . Auth::id())['role_id'];

        if ($userRoleId !== 3 && $userRoleId !== 4 && $userRoleId !== 2) {
            return response()->json(['error' => 'Not enough rights'], 401);
        }

        return $next($request);
    }
}
