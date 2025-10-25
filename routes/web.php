<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Home 控制器路由
Route::get('/', [HomeController::class, 'index'])->name('home');
