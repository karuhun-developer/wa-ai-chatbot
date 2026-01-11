<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'device',
    'as' => 'device.',
], function () {
    Route::resources([
        'devices' => \App\Http\Controllers\Cms\Device\DeviceController::class,
    ]);
});
