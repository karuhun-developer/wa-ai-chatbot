<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'device',
    'as' => 'device.',
], function () {
    Route::resources([
        'devices' => \App\Http\Controllers\Cms\Device\DeviceController::class,
    ]);
    Route::post('devices/{device}/disconnect', [\App\Http\Controllers\Cms\Device\DeviceController::class, 'disconnect'])->name('devices.disconnect');
});
