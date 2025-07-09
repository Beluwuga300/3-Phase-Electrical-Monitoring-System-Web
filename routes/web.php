<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnergiListrikController;

Route::get('/', [EnergiListrikController::class, 'index'])->name('dashboard');
// untuk export excel
Route::get('/export/excel', [EnergiListrikController::class, 'exportExcel'])->name('export.excel');
// untuk AJAX
Route::get('/data-update', [EnergiListrikController::class, 'dataUpdate'])->name('data.update');