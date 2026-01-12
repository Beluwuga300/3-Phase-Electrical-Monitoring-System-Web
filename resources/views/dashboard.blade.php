<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemantauan Energi Listrik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="dashboard-page">
    <header class="topbar">
        <div class="dashboard-container">
            <div class="toolbar">
                <h1 class="page-title">Data Pemantauan Energi Listrik 3 Fasa</h1>
                <div class="toolbar-actions">
                    <a href="{{ route('export.excel') }}" class="btn btn-primary">Download Data (Excel)</a>
                    <select name="interval" id="intervalSelect" class="select">
                        <option value="60" {{ request('interval','1800')=='60' ? 'selected' : '' }}>Per 1 Menit</option>
                        <option value="300" {{ request('interval','1800')=='300' ? 'selected' : '' }}>Per 5 Menit</option>
                        <option value="1800" {{ request('interval','1800')=='1800' ? 'selected' : '' }}>Per 30 Menit</option>
                        <option value="3600" {{ request('interval','1800')=='3600' ? 'selected' : '' }}>Per Jam</option>
                    </select>
                </div>
            </div>
        </div>
    </header>

    <main class="dashboard-container" style="padding-top: 16px; padding-bottom: 24px;">
        <div class="cards-grid">
            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">Tegangan Line-to-Neutral</h4>
                </div>
                <div class="chart-box">
                    <canvas id="chartL2N"></canvas>
                </div>
            </section>

            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">Tegangan Line-To-Line</h4>
                </div>
                <div class="chart-box">
                    <canvas id="chartVLL"></canvas>
                </div>
            </section>

            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">Arus</h4>
                </div>
                <div class="chart-box">
                    <canvas id="chartArus"></canvas>
                </div>
            </section>

            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">Daya Aktif</h4>
                </div>
                <div class="chart-box">
                    <canvas id="chartDaya"></canvas>
                </div>
            </section>

            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">Penggunaan Energi</h4>
                </div>
                <div class="chart-box">
                    <canvas id="chartEnergi"></canvas>
                </div>
            </section>

            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">Frekuensi</h4>
                </div>
                <div class="chart-box chart-box--freq">
                    <canvas id="chartFrekuensi"></canvas>
                </div>
            </section>

            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">Cosphi</h4>
                </div>
                <div class="chart-box">
                    <canvas id="chartCosphi"></canvas>
                </div>
            </section>
        </div>
    </main>

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
    @vite(['resources/js/app.js','resources/js/dashboard.js'])
</body>
</html>

