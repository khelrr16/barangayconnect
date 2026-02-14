<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\LandingController;

Route::get('/', [LandingController::class, 'index']);