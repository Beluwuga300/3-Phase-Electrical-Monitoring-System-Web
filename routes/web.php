<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnergiListrikController;

Route::get('/', [EnergiListrikController::class, 'index'])->name('dashboard');
// untuk export excel
Route::get('/export/excel', [EnergiListrikController::class, 'exportExcel'])->name('export.excel');
// untuk AJAX
Route::get('/data-update', [EnergiListrikController::class, 'dataUpdate'])->name('data.update');

//Uji waktu koneksi
Route::get('/db-test', function () {
    $t0 = microtime(true);
    DB::select('SELECT 1');
    $ms = round((microtime(true) - $t0) * 1000, 3);
    return "Waktu koneksi+query: {$ms} ms";
});

