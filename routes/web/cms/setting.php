<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'settings',
    'as' => 'setting.',
], function () {
    Route::get('/', [App\Http\Controllers\Cms\Setting\SettingController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Cms\Setting\SettingController::class, 'save'])->name('save');
});
