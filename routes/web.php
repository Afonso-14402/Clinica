<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\logginController;



Route::get('/', [logginController::class, 'index'] ) -> name( 'login');
Route::post('/login', [logginController::class, 'loginProcess'])-> name ('login.process');

Route::get('/logout', [logginController::class, 'destroy'])-> name ('login.destroy');




Route::group(['middleware'=> 'auth' ] , function(){

    Route::get('/home', [UserController::class, 'index'] ) -> name( 'index');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/user/change-password', [SettingsController::class, 'changePassword'])->name('user.change-password');

    Route::get('/patients/create', [RegisterController::class, 'create'])->name('patients.create')->middleware('auth','admin:1');
    Route::post('/patients', [RegisterController::class, 'store'])->name('patients.store');

    Route::post('/user/update-avatar', [UserController::class, 'updateAvatar'])->name('user.update.avatar');

    

    
});

