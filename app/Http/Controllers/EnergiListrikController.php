<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\EnergiListrik;
use Illuminate\Support\Facades\DB;
use App\Exports\EnergiListrikExport;
use Maatwebsite\Excel\Facades\Excel;

class EnergiListrikController extends Controller
{
    // untuk menampilkan dashboard
    public function index(Request $request)
    {
        $latestData = EnergiListrik::orderBy('waktu', 'desc')->take(20)->get()->reverse();
        //$data = EnergiListrik::orderBy('waktu', 'asc')->take(20)->get();

        $realtimeLabels = $latestData->pluck('waktu')->map(function ($item) {
            return \Carbon\Carbon::parse($item)->format('H:i:s');
        });

        // Tegangan (V)
        $tegangan_r = $latestData->pluck('tegangan_r');
        $tegangan_s = $latestData->pluck('tegangan_s');
        $tegangan_t = $latestData->pluck('tegangan_t');
        $v_rs = $latestData->pluck('voltage_rs');
        $v_st = $latestData->pluck('voltage_st');
        $v_tr = $latestData->pluck('voltage_tr');

        // Arus (A)
        $arus_r = $latestData->pluck('arus_r');
        $arus_s = $latestData->pluck('arus_s');
        $arus_t = $latestData->pluck('arus_t');

        // Daya (W)
        $daya_r = $latestData->pluck('daya_r');
        $daya_s = $latestData->pluck('daya_s');
        $daya_t = $latestData->pluck('daya_t');

        $interval = $request->get('interval', 1800); // Default 30 menit (1800 detik)
        // Kumpulin data untuk ditampilkan dalam bentuk interval
        $aggregatedData = DB::table('energi_listrik')
            ->selectRaw('
                FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(waktu)/?) * ?) as waktu_interval,
                MAX(energi_r) as energi_r,
                MAX(energi_s) as energi_s,
                MAX(energi_t) as energi_t,
                AVG(frekuensi_r) as frekuensi_r,
                AVG(frekuensi_s) as frekuensi_s,
                AVG(frekuensi_t) as frekuensi_t,
                AVG(faktor_daya_r) as faktor_daya_r,
                AVG(faktor_daya_s) as faktor_daya_s,
                AVG(faktor_daya_t) as faktor_daya_t
            ', [$interval, $interval])
            ->groupBy('waktu_interval')
            ->orderBy('waktu_interval', 'asc')
            ->get();

        $intervalLabels = $aggregatedData->pluck('waktu_interval')->map(function ($item) {
            return \Carbon\Carbon::parse($item)->format('H:i');
        });

        // energi (kWh)
        $energi_r = $aggregatedData->pluck('energi_r');
        $energi_s = $aggregatedData->pluck('energi_s');
        $energi_t = $aggregatedData->pluck('energi_t');

        //frekuensi(Hz)
        $frekuensi_r = $aggregatedData->pluck('frekuensi_r');
        $frekuensi_s = $aggregatedData->pluck('frekuensi_s');
        $frekuensi_t = $aggregatedData->pluck('frekuensi_t');

        // faktor daya 
        $faktor_daya_r = $aggregatedData->pluck('faktor_daya_r');
        $faktor_daya_s = $aggregatedData->pluck('faktor_daya_s');
        $faktor_daya_t = $aggregatedData->pluck('faktor_daya_t');

        // Kirim ke view
        return view('dashboard', compact(
            'realtimeLabels', // Labels untuk grafik real-time
            'intervalLabels', // Labels untuk grafik interval
            'tegangan_r', 
            'tegangan_s', 
            'tegangan_t',
            'v_rs',
            'v_st',
            'v_tr',
            'arus_r',
            'arus_s',
            'arus_t',
            'daya_r', 
            'daya_s', 
            'daya_t',
            'energi_r',
            'energi_s',
            'energi_t',
            'frekuensi_r',
            'frekuensi_s',
            'frekuensi_t',
            'faktor_daya_r',
            'faktor_daya_s',
            'faktor_daya_t',
            'interval'
        ));
    }
    // untuk export data energi listrik ke Excel
    public function exportExcel()
    {
        return Excel::download(new EnergiListrikExport, 'energi_listrik-' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }
}
