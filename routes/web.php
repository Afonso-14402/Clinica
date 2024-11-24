<?php

use App\Http\Controllers\LogginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// routes para o login
Route::get('/', [LogginController::class, 'index'] ) -> name( 'login');
Route::post('/login', [LogginController::class, 'loginProcess'])-> name ('login.process');

// routes para o usuarios
Route::get('/home', [UserController::class, 'index'] ) -> name( 'user.index');