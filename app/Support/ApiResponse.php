<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

/**
 * Jedinstven format JSON odgovora za admin AJAX akcije.
 *
 * Uspeh:  { status: "success", message: "...", data: ... }
 * Greška: { status: "error",   message: "...", errors: {...} }
 *
 * Validacione greške (422) Laravel automatski vraća kao { message, errors }
 * iz Form Request klasa — admin.js (handleAjaxError) zna oba oblika.
 */
trait ApiResponse
{
    protected function ok(string $message, mixed $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function fail(string $message, array $errors = [], int $code = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
