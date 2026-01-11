<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => to_route('cms.dashboard'))->name('home');

require 'web/settings.php';
require 'web/cms.php';
