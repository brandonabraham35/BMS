<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data, ?string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse(string $message, int $code, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    /**
     * @deprecated Use successResponse
     */
    protected function success($data, ?string $message = null, int $code = 200): JsonResponse
    {
        return $this->successResponse($data, $message, $code);
    }

    /**
     * @deprecated Use errorResponse
     */
    protected function error(string $message, int $code, $errors = null): JsonResponse
    {
        return $this->errorResponse($message, $code, $errors);
    }
}
