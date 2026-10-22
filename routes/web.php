<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('employees.index');
    })->name('dashboard');

    // Dependent Dropdowns AJAX endpoints
    Route::get('/api/countries/{country}/states', [EmployeeController::class, 'getStates'])->name('ajax.states');
    Route::get('/api/states/{state}/cities', [EmployeeController::class, 'getCities'])->name('ajax.cities');

    // Employee Resource CRUD
    Route::resource('employees', EmployeeController::class);

    // Profile Routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Password Update Route
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

});

require __DIR__.'/auth.php';