<?php

namespace App\Http\Middleware;

use App\Http\Response\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailConfirmed
{

    /**
     * @param $request
     * @param Closure $next
     * @return JsonResponse
     */
    public function handle($request, Closure $next): JsonResponse
    {
        $user = $request->user();

        if ($user && !$user->isEmailConfirmed()) {
            return response()->json(new ApiResponse('Forbidden', 403, false), 403);
        }

        return $next($request);
    }
}
