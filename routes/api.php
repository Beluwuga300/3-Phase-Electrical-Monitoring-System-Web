<?php

use Illuminate\Http\Request;
use App\Models\EnergiListrik;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/simpan-data', function (Request $request) {
    Log::info('Data diterima dari Arduino:', $request->query());

    Log::info('IP pengirim:', ['ip' => $request->ip()]);

    EnergiListrik::create($request->all());
    return response()->json(['status' => 'success']);
});
