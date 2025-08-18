<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemantauan Energi Listrik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    <h1>Data Pemantauan Energi Listrik 3 Fasa</h1>
    <div style="margin: 20px 0;">
        <a href="{{ route('export.excel') }}" 
        style="background-color: #217346; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
        Download Data (Excel)
        </a>
    </div>
    <h4 class="text-lg font-bold mt-6">Tegangan Line-to-Neutral</h4>
    <canvas id="chartL2N" height="100"></canvas>
    <h4 class="text-lg font-bold mt-6">Tegangan Line-To-Line</h4>
    <canvas id="chartVLL" height="100"></canvas>
    <h4 class="text-lg font-bold mt-6">Arus</h4>
    <canvas id="chartArus" height="100"></canvas>
    <h4 class="text-lg font-bold mt-6">Daya Aktif</h4>
    <canvas id="chartDaya" height="100"></canvas>
    <select name="interval" id="intervalSelect" style="padding: 5px; font-size: 16px;">
        <option value="60" {{ request('interval','1800')=='60' ? 'selected' : '' }}>Per 1 Menit</option>
        <option value="300" {{ request('interval','1800')=='300' ? 'selected' : '' }}>Per 5 Menit</option>
        <option value="1800" {{ request('interval','1800')=='1800' ? 'selected' : '' }}>Per 30 Menit</option>
        <option value="3600" {{ request('interval','1800')=='3600' ? 'selected' : '' }}>Per Jam</option>
    </select>
    <h4 class="text-lg font-bold mt-6">Penggunaan Energi</h4>
    <canvas id="chartEnergi" height="100"></canvas>
    <h4 class="text-lg font-bold mt-6">Frekuensi</h4>
    <canvas id="chartFrekuensi" height="80"></canvas>
    <h4 class="text-lg font-bold mt-6">Cosphi</h4>
    <canvas id="chartCosphi" height="100"></canvas>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
    <script>
        window.DASHBOARD_CONFIG = {
            lastTimestamp: {!! isset($lastTimestamp) && $lastTimestamp !== null ? $lastTimestamp : 'null' !!},
            routes: {
                update: "{{ route('data.update') }}",
                dashboard: "{{ route('dashboard') }}",
            },
            data: {
                realtimeLabels: {!! json_encode($realtimeLabels) !!},
                tegangan_r: {!! json_encode($tegangan_r) !!},
                tegangan_s: {!! json_encode($tegangan_s) !!},
                tegangan_t: {!! json_encode($tegangan_t) !!},
                v_rs: {!! json_encode($v_rs) !!},
                v_st: {!! json_encode($v_st) !!},
                v_tr: {!! json_encode($v_tr) !!},
                arus_r: {!! json_encode($arus_r) !!},
                arus_s: {!! json_encode($arus_s) !!},
                arus_t: {!! json_encode($arus_t) !!},
                daya_r: {!! json_encode($daya_r) !!},
                daya_s: {!! json_encode($daya_s) !!},
                daya_t: {!! json_encode($daya_t) !!},
                intervalLabels: {!! json_encode($intervalLabels) !!},
                energi_r: {!! json_encode($energi_r) !!},
                energi_s: {!! json_encode($energi_s) !!},
                energi_t: {!! json_encode($energi_t) !!},
                frekuensi_r: {!! json_encode($frekuensi_r) !!},
                frekuensi_s: {!! json_encode($frekuensi_s) !!},
                frekuensi_t: {!! json_encode($frekuensi_t) !!},
                faktor_daya_r: {!! json_encode($faktor_daya_r) !!},
                faktor_daya_s: {!! json_encode($faktor_daya_s) !!},
                faktor_daya_t: {!! json_encode($faktor_daya_t) !!},
            }
        };
    </script>
    @vite('resources/js/dashboard.js')
</body>
</html>
