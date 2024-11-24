<?php

use App\Http\Controllers\logginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// routes para o login
Route::get('/', [logginController::class, 'index'] ) -> name( 'login');
Route::post('/login', [logginController::class, 'loginProcess'])-> name ('login.process');
Route::get('/logout', [logginController::class, 'destroy'])-> name ('login.destroy');
//route privadas so com login 

Route::group(['middleware'=> 'auth' ] , function(){

    Route::get('/home', [UserController::class, 'index'] ) -> name( 'user.index');

});
  