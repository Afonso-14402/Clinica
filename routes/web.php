<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterdoctorController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\logginController;
use App\Http\Controllers\AppointmentController;



Route::get('/', [logginController::class, 'index'] ) -> name( 'login');
Route::post('/login', [logginController::class, 'loginProcess'])-> name ('login.process');
Route::get('/logout', [logginController::class, 'destroy'])-> name ('login.destroy');




Route::group(['middleware'=> 'auth' ] , function(){

    Route::get('/home', [UserController::class, 'index'] ) -> name( 'index');

    

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/activities', [DashboardController::class, 'fetchActivities']);
    

    
    Route::get('/events', [AppointmentController::class, 'getEvents'])->name('events');
    Route::get('/appointments/events', [AppointmentController::class, 'getEvents'])->name('appointments.events');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/user/change-password', [SettingsController::class, 'changePassword'])->name('user.change-password');
    Route::post('/user/update-avatar', [UserController::class, 'updateAvatar'])->name('user.update.avatar');
    
    Route::get('/registar/create/paciente', [RegisterController::class, 'create'])->name('registar.create') ->middleware(['auth', 'admin:1,2']);
    Route::post('/registar/paciente', [RegisterController::class, 'paciente'])->name('registar.paciente');

    Route::get('/registar/create/medico', [RegisterdoctorController::class, 'create'])->name('registar.create-m') ->middleware(['auth', 'admin:1']);
    Route::post('/registar/medico', [RegisterdoctorController::class, 'medico'])->name('registar.medico');
    


   

    

    

    
});



Route::get('/autocomplete/doctors', function () {
    $search = request('q');

    $doctors = User::where('name', 'like', '%' . $search . '%')
        ->whereHas('role', function ($query) {
            $query->where('role', 'Doctor'); // Ajuste conforme necessário
        })
        ->take(10)
        ->get();

    return response()->json($doctors);
});

Route::get('/doctor/{id}/specialties', function ($id) {
    $doctor = User::findOrFail($id);
    return $doctor->specialties; // Supondo que existe um relacionamento entre médicos e especialidades
});

Route::get('/autocomplete/patient', function () {
    $search = request('q');

    $patient = User::where('name', 'like', '%' . $search . '%')
        ->whereHas('role', function ($query) {
            $query->where('role', 'Patient'); // Ajuste conforme necessário
        })
        ->take(10)
        ->get();

    return response()->json($patient);
});

