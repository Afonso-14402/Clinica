<?php

use App\Http\Controllers\logginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// routes para o login
Route::get('/', [logginController::class, 'index'] ) -> name( 'login');

// routes para o usuarios
Route::get('/home', [UserController::class, 'index'] ) -> name( 'user.index');