<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnergiListrikController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [EnergiListrikController::class, 'index'])->name('dashboard');
Route::get('/export/excel', [EnergiListrikController::class, 'exportExcel'])->name('export.excel');