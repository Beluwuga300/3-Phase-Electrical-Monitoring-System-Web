<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\EnergiListrik;
use Illuminate\Support\Facades\DB;


class EnergiListrikController extends Controller
{
    public function index(Request $request)
    {
        $data = EnergiListrik::orderBy('waktu', 'asc')->take(20)->get();

        // $labels = $data->pluck('waktu')->map(function ($item) {
        //     return $item->format('H:i:s');
        // });
        $labels = $data->pluck('waktu')->map(function ($item) {
            return Carbon::parse($item)->format('H:i:s');
        });
        $tegangan_r = $data->pluck('tegangan_r');
        $tegangan_s = $data->pluck('tegangan_s');
        $tegangan_t = $data->pluck('tegangan_t');

        $arus_r = $data->pluck('arus_r');
        $arus_s = $data->pluck('arus_s');
        $arus_t = $data->pluck('arus_t');

        // Hitung VLL
        $v_rs = [];
        $v_st = [];
        $v_tr = [];

        foreach ($data as $row) {
            $vr = $row->tegangan_r;
            $vs = $row->tegangan_s;
            $vt = $row->tegangan_t;

            $v_rs[] = sqrt(pow($vr, 2) + pow($vs, 2) - 2 * $vr * $vs * cos(deg2rad(120)));
            $v_st[] = sqrt(pow($vs, 2) + pow($vt, 2) - 2 * $vs * $vt * cos(deg2rad(120)));
            $v_tr[] = sqrt(pow($vt, 2) + pow($vr, 2) - 2 * $vt * $vr * cos(deg2rad(120)));
        }
        $interval = $request->get('interval', 1800); // default 30 menit

        // Agregasi data per waktu
        $data = DB::table('energi_listrik')
            ->selectRaw('
                FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(waktu)/?) * ?) as waktu_interval,
                MAX(energi_r) as energi_r,
                MAX(energi_s) as energi_s,
                MAX(energi_t) as energi_t
            ', [$interval, $interval])
            ->groupBy('waktu_interval')
            ->orderBy('waktu_interval')
            ->get();

        $labels = $data->pluck('waktu_interval')->map(function ($item) {
            return \Carbon\Carbon::parse($item)->format('H:i');
        });

        $energi_r = $data->pluck('energi_r');
        $energi_s = $data->pluck('energi_s');
        $energi_t = $data->pluck('energi_t');

        $data = DB::table('energi_listrik')
            ->selectRaw('
                FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(waktu)/?) * ?) as waktu_interval,
                MAX(frekuensi_r) as frekuensi_r,
                MAX(frekuensi_s) as frekuensi_s,
                MAX(frekuensi_t) as frekuensi_t
            ', [$interval, $interval])
            ->groupBy('waktu_interval')
            ->orderBy('waktu_interval')
            ->get();

        $labels = $data->pluck('waktu_interval')->map(function ($item) {
            return \Carbon\Carbon::parse($item)->format('H:i');
        });

        $frekuensi_r = $data->pluck('frekuensi_r');
        $frekuensi_s = $data->pluck('frekuensi_s');
        $frekuensi_t = $data->pluck('frekuensi_t');

        // Ambil data cosphi per interval
        $data = DB::table('energi_listrik')
            ->selectRaw('
                FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(waktu)/?) * ?) as waktu_interval,
                MAX(faktor_daya_r) as faktor_daya_r,
                MAX(faktor_daya_s) as faktor_daya_s,
                MAX(faktor_daya_t) as faktor_daya_t
            ', [$interval, $interval])
            ->groupBy('waktu_interval')
            ->orderBy('waktu_interval')
            ->get();

        // Labels waktu dan data cosphi per fasa
        $labels = $data->pluck('waktu_interval')->map(function ($item) {
            return \Carbon\Carbon::parse($item)->format('H:i');
        });

        $faktor_daya_r = $data->pluck('faktor_daya_r');
        $faktor_daya_s = $data->pluck('faktor_daya_s');
        $faktor_daya_t = $data->pluck('faktor_daya_t');


    // $energi = $data->pluck('energi');
        return view('dashboard', compact(
            'labels', 
            'tegangan_r', 
            'tegangan_s', 
            'tegangan_t',
            'v_rs',
            'v_st',
            'v_tr',
            'arus_r',
            'arus_s',
            'arus_t',
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
}
