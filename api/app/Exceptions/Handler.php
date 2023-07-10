<?php

namespace App\Exceptions;

use App\Http\Response\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return $this->handleNotFoundException($e, $request);
        });
    }

    /**
     * Handle the "Not Found" exception.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\NotFoundHttpException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleNotFoundException(NotFoundHttpException $e, $request)
    {
        if ($request->expectsJson()) {
            return ApiResponse::error('The resource was not found', 404);
        }

        return parent::render($request, $e);
    }
}
