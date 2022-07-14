<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

if (!function_exists('successResponse')) {
    function successResponse(array | JsonResource $data = [], ?int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => App\Helpers\Enums\ResponseStatus::OK,
            'data'   => $data,
        ], $statusCode);
    }
}

if (!function_exists('errorResponse')) {
    function errorResponse(array $errors, ?int $statusCode = 422): JsonResponse
    {
        return response()->json([
            'status' => App\Helpers\Enums\ResponseStatus::FAILED,
            'errors' => $errors,
        ], $statusCode);
    }
}
