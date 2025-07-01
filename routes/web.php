<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnergiListrikController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/', [EnergiListrikController::class, 'index'])->name('dashboard');