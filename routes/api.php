<?php

use App\Http\Controllers\Api\VacationController;
use Illuminate\Support\Facades\Route;

Route::post('/login', ['as' => 'login', 'uses' => 'AuthController@login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('vacations')->name('vacation.')->group(function () {
        Route::get('/', [VacationController::class, 'index'])->name('list.vacations');
        Route::get('/show/{id}', [VacationController::class, 'show'])->name('show.vacation');
        Route::post('/create', [VacationController::class, 'store'])->name('create.vacation');
        Route::put('/update/{id}', [VacationController::class, 'update'])->name('edit.vacation');
        Route::delete('/delete/{id}', [VacationController::class, 'destroy'])->name('delete.vacation');
        Route::get('/pdf/{id}', [VacationController::class, 'generate'])->name('generate.vacation.pdf');
    });
});
