<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    protected function success($data, ?string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(?string $message, int $code, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
