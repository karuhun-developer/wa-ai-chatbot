<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('v1/webhook/{token}', function (Request $request, $token) {
    Log::info('Received webhook with token: '.$token, ['request' => $request->all()]);

    return response()->json(['status' => 'success'], 200);
});
