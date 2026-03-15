<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    /**
     * Send a standardized success response.
     */
    protected function success(mixed $data, string $message = 'Success', int $code = 200): JsonResponse
    {
        $response = [
            'status' => 'success',
            'message' => $message,
        ];

        if (is_array($data) && isset($data['data']) && (isset($data['links']) || isset($data['meta']))) {
            $response = array_merge($response, $data);
        } else {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Send a standardized error response.
     */
    protected function error(string $message, int $code = 400, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
