<?php

use App\Http\Controllers\AppointmentController;
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
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/appointment', function () {
    return 'gg';
})->middleware(['auth','verified'])->name('appointment');

Route::resource('/appointments',AppointmentController::class)->names([
    'index' => 'appointments.list',
    'create' => 'appointments.create',
    'store' => 'appointments.save',
    'show' => 'appointments.view',
    'edit' => 'appointments.modify',
    'update' => 'appointments.change',
    'destroy' => 'appointments.delete',
]);

// Mail sending

Route::post('/mail/send',function (Request $request) {
    if (!$request->to) {
        return to_route('appointments.list');
    }
    Mail::to($request->to)->send(new FollowUp($request->subject, $request->message));
    return to_route('appointments.list');
})->middleware(['auth','verified']);


require __DIR__.'/settings.php';
