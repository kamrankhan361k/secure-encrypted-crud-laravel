<?php

use App\Http\Controllers\SecureDataController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Secure Data Routes
Route::resource('secure-data', SecureDataController::class);
Route::get('secure-data/verify/{id}', [SecureDataController::class, 'verifyEncryption'])
     ->name('secure-data.verify');
Route::post('secure-data/{id}/toggle-status', [SecureDataController::class, 'toggleStatus'])
     ->name('secure-data.toggle-status');

// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
