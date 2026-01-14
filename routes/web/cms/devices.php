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
    Route::get('devices/{device}/messages/test', [\App\Http\Controllers\Cms\Device\DeviceMessageController::class, 'test'])->name('devices.messages.test');
    Route::post('devices/{device}/messages/send', [\App\Http\Controllers\Cms\Device\DeviceMessageController::class, 'send'])->name('devices.messages.send');
});
