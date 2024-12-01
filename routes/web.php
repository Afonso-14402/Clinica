<?php


use App\Http\Controllers\logginController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;


Route::get('/', [logginController::class, 'index'] ) -> name( 'login');
Route::post('/login', [logginController::class, 'loginProcess'])-> name ('login.process');
Route::get('/logout', [logginController::class, 'destroy'])-> name ('login.destroy');
    
Route::get('/dashboard', function(){
    return view('dashboard');
})->middleware('auth','admin:2')-> name( 'dashboard');;
;


//route privadas so com login 

Route::group(['middleware'=> 'auth' ] , function(){

    Route::get('/home', [UserController::class, 'index'] ) -> name( 'user.index');
    Route::get('/register.pacinte', [RegisterController::class, 'show'])->name('register.show')->middleware('auth','admin:2');
    Route::post('/register.pacinte', [RegisterController::class, 'store'])->name('register.store');
    
});

