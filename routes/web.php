<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MailController;
use App\Mail\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return to_route('appointments.list');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','verified'])->group(function () {
    Route::resource('appointments',AppointmentController::class)->names([
    'index' => 'appointments.list',
    'create' => 'appointments.create',
    'store' => 'appointments.save',
    'show' => 'appointments.view',
    'edit' => 'appointments.modify',
    'update' => 'appointments.change',
    'destroy' => 'appointments.delete',
    ]);

    Route::post('/mail/send',[MailController::class,'sendMail']);


    Route::get('/export/appointments',[ExportController::class,'exportCSV']);

});

require __DIR__.'/settings.php';
