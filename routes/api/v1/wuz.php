<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'wuz',
    'as' => 'wuz.',
], function () {
    Route::post('/message', [\App\Http\Controllers\Api\V1\WuzController::class, 'send'])->name('handle');
});
