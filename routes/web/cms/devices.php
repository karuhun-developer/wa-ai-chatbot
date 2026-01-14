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
    Route::get('devices/{device}/webhooks/manage', [\App\Http\Controllers\Cms\Device\DeviceWebhookController::class, 'manage'])->name('devices.webhooks.manage');
    Route::post('devices/{device}/webhooks/sync', [\App\Http\Controllers\Cms\Device\DeviceWebhookController::class, 'sync'])->name('devices.webhooks.sync');
});

