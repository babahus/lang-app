<?php

namespace App\Http\Response;

use Illuminate\Http\JsonResponse;

final class ApiResponse extends JsonResponse
{
    public function __construct($data = null, $status = 200, $success = true, $headers = [], $options = 0)
    {
        $payload = [
            'success' => $success,
            'data' => $data,
        ];

        parent::__construct($payload, $status, $headers, $options);
    }

    public static function error($message, $status = 400): ApiResponse
    {
        return new self(['message' => $message], $status);
    }
}
