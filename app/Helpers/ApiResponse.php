<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(
        string $message,
        int $statusCode,
        mixed $data,

    ):JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ]);
    }
    public static function error(
        string $message,
        int $statusCode,
        mixed $error,
    ): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
            'error' => $error,
        ]);
    }

}
