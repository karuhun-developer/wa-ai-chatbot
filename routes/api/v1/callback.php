<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'webhook',
    'as' => 'webhook.',
], function () {
    Route::post('/{token}', [\App\Http\Controllers\Api\V1\CallbackController::class, 'store'])->name('handle');
});
