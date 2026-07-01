<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json(['status' => 'OK', 'timestamp' => now()]);
    });

    Route::get('/ready', function () {
        // Check DB connection
        try {
            DB::connection()->getPdo();

            return response()->json(['status' => 'Ready']);
        } catch (Exception $e) {
            return response()->json(['status' => 'Not Ready', 'error' => $e->getMessage()], 503);
        }
    });

    Route::get('/status', function () {
        return response()->json([
            'status' => 'Operational',
            'version' => '1.0.0',
            'environment' => app()->environment(),
        ]);
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
});
