<?php

use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterdoctorController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\logginController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorAgendaController;
use App\Http\Controllers\AppointmentHistoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FamilyDoctorController;
use App\Http\Controllers\AdminController;



Route::get('/', [logginController::class, 'index'] ) -> name( 'login');
Route::post('/login', [logginController::class, 'loginProcess'])-> name ('login.process');
Route::get('/logout', [logginController::class, 'destroy'])-> name ('login.destroy');
Route::get('/lixo', [logginController::class, 'destroy'])-> name ('lixo.destroy');



Route::group(['middleware'=> 'auth' ] , function(){

    Route::get('/home/admin', [UserController::class, 'index'] ) -> name( 'index');
    Route::get('/home/pacientes', [UserController::class, 'index'] ) -> name( 'patient.index');
    Route::get('/home/medico', [UserController::class, 'index'] ) -> name( 'doctor.index');
    Route::get('/home', [UserController::class, 'redirectToHome'])->name('home');


    Route::post('/appointments/{id}/status', [AppointmentController::class, 'updateStatus']);
    Route::get('/doctors/{id}/schedule', [AppointmentController::class, 'getSchedule']);
    Route::get('/doctors/{doctorId}/appointments', [AppointmentController::class, 'getDoctorAppointments']);

    Route::post('/appointments/request', [AppointmentController::class, 'requestAppointment'])->name('requestAppointment');
    Route::get('/appointments/pending', [AppointmentController::class, 'showPendingAppointments'])->name('appointments.pending');
    Route::put('/appointments/{id}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
    Route::get('/doctor/appointments', [AppointmentController::class, 'getDoctorAppointments']);

    Route::put('/appointments/{appointment}/approve', [AppointmentController::class, 'approve'])->name('appointments.approve');
    Route::put('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');




    Route::get('/consulta/iniciar/{id}', [ConsultaController::class, 'iniciarConsulta'])->name('consulta.iniciar');
    Route::post('/consulta/salvar/{id}', [ConsultaController::class, 'salvarRelatorio'])->name('consulta.salvarRelatorio');


    
    Route::get('/list', [ListController::class, 'index'])->name('list.index');
    Route::resource('doctors', ListController::class);

    
 

    
    
    Route::get('/list', [ListController::class, 'index'])->name('list.index');
    Route::get('/doctors/{doctor}/schedules', [DoctorScheduleController::class, 'getDoctorSchedules']);
    Route::get('/doctor-schedules/{RoutedoctorId}', [DoctorScheduleController::class, 'index']);
    Route::post('/doctor-schedules/{doctorId}', [DoctorScheduleController::class, 'store'])->name('doctor.schedules.store');
    Route::post('/doctors/{doctor}/toggle-status', [ListController::class, 'toggleStatus'])->name('doctors.toggleStatus');
    Route::get('/search-doctors', [ListController::class, 'search'])->name('doctors.search');



    

    Route::get('/list/patients', [ListController::class, 'getPatients'])->name('list.listpatient');
    Route::post('/patients/{patient}/toggle-status', [ListController::class, 'toggleStatusPatients'])->name('patients.toggle-status');
    Route::delete('/patients/{patient}', [ListController::class, 'destroyPatients'])->name('patients.destroy');
    Route::get('/patient-reports/{patientId}', [ListController::class, 'getPatientReports']);





   

    
    Route::get('/events', [AppointmentController::class, 'getEvents'])->name('events');
    Route::get('/appointments/events', [AppointmentController::class, 'getEvents'])->name('appointments.events');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/history', [AppointmentHistoryController::class, 'index'])->name('appointments.history');


    
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/user/change-password', [SettingsController::class, 'changePassword'])->name('user.change-password');
    Route::post('/user/update-avatar', [UserController::class, 'updateAvatar'])->name('user.update.avatar');
    
    
    Route::post('/registar-paciente', [RegisterController::class, 'registarPaciente'])->name('registar.paciente');
    Route::post('/registar-medico', [RegisterController::class, 'store'])->name('registar.medico');
    

    
   

    Route::get('/available-times', [DoctorAgendaController::class, 'getAvailableTimes']);
    

    
});



Route::get('/autocomplete/doctors', function () {
    $search = request('q');

    $doctors = User::where('name', 'like', '%' . $search . '%')
        ->whereHas('role', function ($query) {
            $query->where('role', 'Doctor'); 
        })
        ->take(10)
        ->get();

    return response()->json($doctors);
});

Route::get('/doctor/{id}/specialties', function ($id) {
    $doctor = User::findOrFail($id);
    return $doctor->specialties; 
});

Route::get('/autocomplete/patient', function () {
    $search = request('q');

    $patient = User::where('name', 'like', '%' . $search . '%')
        ->whereHas('role', function ($query) {
            $query->where('role', 'Patient'); 
        })
        ->take(10)
        ->get();

    return response()->json($patient);
});

Route::get('/patients/{patient}/info', [ListController::class, 'getPatientInfo']);
Route::get('/patients/{patient}/appointments', [ListController::class, 'getPatientAppointments']);

Route::get('/appointments/{id}/report', [AppointmentController::class, 'getReport'])->name('appointments.report');
Route::get('/patient-details/{id}', [ListController::class, 'getPatientDetails'])->name('patient.details');

Route::get('/doctor/{doctorId}/works-saturday', [DoctorAgendaController::class, 'checkWorksSaturday']);

Route::get('/doctor/{doctorId}/check-availability', [DoctorAgendaController::class, 'checkAvailability']);

// Rota para médico ver pacientes
Route::get('/doctor/patients', [DoctorAgendaController::class, 'patients'])->name('doctor.patient');


Route::post('/patient/{id}/update', [PatientController::class, 'update'])->name('patient.update');

// Rotas para Médicos de Família
Route::get('/family', [FamilyDoctorController::class, 'index'])->name('family.index');
Route::get('/family/create', [FamilyDoctorController::class, 'create'])->name('family.create');
Route::post('/family', [FamilyDoctorController::class, 'store'])->name('family.store');
Route::get('/family/edit/{id}', [FamilyDoctorController::class, 'edit'])->name('family.edit');
Route::put('/family/{id}', [FamilyDoctorController::class, 'update'])->name('family.update');
Route::delete('/family/{id}', [FamilyDoctorController::class, 'destroy'])->name('family.destroy');



Route::get('/list/admin', [AdminController::class, 'index'])->name('list.listadmin');

Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
