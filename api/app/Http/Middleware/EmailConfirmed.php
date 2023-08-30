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

        if (!$user || !$user->isEmailConfirmed()) {
            return new ApiResponse('You need to confirm your email to change it ', 403, false);
        }

        return $next($request);
    }
}
