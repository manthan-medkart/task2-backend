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
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
    public static function error(
        string $message,
        int $statusCode,
        mixed $error,
    ): JsonResponse
    {
        return response()->json([
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
        ], $statusCode);
    }

}
