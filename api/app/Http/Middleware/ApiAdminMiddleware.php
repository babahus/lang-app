<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $usersRolesIds = Auth::user()->roles()->pluck('id')->toArray();
        if (!in_array(3,$usersRolesIds) || !in_array(4,$usersRolesIds)) {
            return response()->json(['error' => 'Not enough rights'], 401);
        }

        return $next($request);
    }
}
