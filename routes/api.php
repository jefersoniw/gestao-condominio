<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BilletController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\FoundAndLostController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WallController;
use App\Http\Controllers\WarningController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return [
        'pong' => true
    ];
});

Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');

Route::middleware('auth:api')->group(function () {

    Route::post('/auth/validate', [AuthController::class, 'validateToken'])->name('auth.validate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    //Mural de avisos
    Route::get('/walls', [WallController::class, 'getAll'])->name('wall.getAll');
    Route::post('/wall/{id}/like', [WallController::class, 'like'])->name('wall.like');

    //Documentos
    Route::get('/docs', [DocController::class, 'getAll'])->name('doc.getAll');

    //Livro de ocorrencias
    Route::get('/warnings', [WarningController::class, 'getMyWarning'])->name('warning.getMyWarning');
    Route::post('/warning/file', [WarningController::class, 'addWarningFile'])->name('warning.addWarningFile');
    Route::post('/warning', [WarningController::class, 'setWarning'])->name('warning.setWarning');

    //Boletos
    Route::get('/billets', [BilletController::class, 'getAll'])->name('billet.getAll');

    //Achados e perdidos
    Route::get('/foundAndLost', [FoundAndLostController::class, 'getAll'])->name('foundAndLost.getAll');
    Route::post('/foundAndLost', [FoundAndLostController::class, 'insert'])->name('foundAndLost.insert');
    Route::put('/foundAndLost/{id}', [FoundAndLostController::class, 'update'])->name('foundAndLost.update');

    //Unidade
    Route::get('/unit/{id}', [UnitController::class, 'getInfo'])->name('unit.getInfo');
    Route::post('/unit/{id}/addPerson', [UnitController::class, 'addPerson'])->name('unit.addPerson');
    Route::post('/unit/{id}/addVehicle', [UnitController::class, 'addVehicle'])->name('unit.addVehicle');
    Route::post('/unit/{id}/addPet', [UnitController::class, 'addPet'])->name('unit.addPet');
    Route::post('/unit/{id}/removePerson', [UnitController::class, 'removePerson'])->name('unit.removePerson');
    Route::post('/unit/{id}/removeVehicle', [UnitController::class, 'removeVehicle'])->name('unit.removeVehicle');
    Route::post('/unit/{id}/removePet', [UnitController::class, 'removePet'])->name('unit.removePet');

    //Reservas
    Route::get('/reservations', [ReservationController::class, 'getReservations'])->name('reservations.getReservations');
    Route::post('/reservation/{id}', [ReservationController::class, 'setReservation'])->name('reservations.setReservation');

    Route::get('/reservation/{id}/disabledDates', [ReservationController::class, 'getDisabledDates'])->name('reservation.getDisabledDates');
    Route::get('/reservation/{id}/times', [ReservationController::class, 'getTimes'])->name('reservation.getTimes');

    Route::get('/myReservations', [ReservationController::class, 'getMyReservation'])->name('reservations.getMyReservation');
    Route::delete('/myReservations/{id}', [ReservationController::class, 'delMyReservation'])->name('reservations.delMyReservation');
});
