<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemantauan Energi Listrik</title>
</head>
<body>
    <h1>Data Pemantauan Energi Listrik 3 Fasa</h1>
    <h4 class="text-lg font-bold mt-6">Tegangan Line-to-Neutral</h4>
    <canvas id="chartL2N" height="100"></canvas>
    <h4 class="text-lg font-bold mt-6">Tegangan Line-To-Line</h4>
    <canvas id="chartVLL" height="100"></canvas>
    <h4 class="text-lg font-bold mt-6">Arus</h4>
    <canvas id="chartArus" height="100"></canvas>
    <h4 class="text-lg font-bold mt-6">Daya Aktif</h4>
    <canvas id="chartDaya" height="100"></canvas>
    <h4 class="text-lg font-bold mt-6">Penggunaan Energi</h4>
    <form method="GET" action="{{ url('/') }}">
        <select name="interval" onchange="this.form.submit()" getElementId="chartEnergi">
            <option value="300" {{ $interval == 300 ? 'selected' : '' }}>Per 5 Menit</option>
            <option value="1800" {{ $interval == 1800 ? 'selected' : '' }}>Per 30 Menit</option>
            <option value="3600" {{ $interval == 3600 ? 'selected' : '' }}>Per Jam</option>
        </select>
    </form>
    <canvas id="chartEnergi" height="100"></canvas>
    <canvas id="chartFrekuensi" height="80"></canvas>
    <canvas id="chartCosphi" height="100"></canvas>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Untuk Grafik Tegangan
        const ctxVLN = document.getElementById('chartL2N').getContext('2d');
        const chart = new Chart(ctxVLN, {
            type: 'line',
            data: {
                labels: {!! json_encode($realtimeLabels) !!},
                datasets: [
                    {
                        label: 'Tegangan R',
                        data: {!! json_encode($tegangan_r) !!},
                        borderColor: 'red',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Tegangan S',
                        data: {!! json_encode($tegangan_s) !!},
                        borderColor: 'green',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Tegangan T',
                        data: {!! json_encode($tegangan_t) !!},
                        borderColor: 'blue',
                        fill: false,
                        tension: 0.4
                    },
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Tegangan (V)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Waktu'
                        }
                    }
                }
            }
            
        });

        // Untuk Grafik Tegangan 3 Fasa
        const ctxVLL = document.getElementById('chartVLL').getContext('2d');
        const chartVLL = new Chart(ctxVLL, {
            type: 'line',
            data: {
                labels: {!! json_encode($realtimeLabels) !!},
                datasets: [
                    {
                        label: 'Tegangan RS',
                        data: {!! json_encode($v_rs) !!},
                        borderColor: 'rgba(255, 99, 132, 1)',
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'Tegangan ST',
                        data: {!! json_encode($v_st) !!},
                        borderColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'Tegangan TR',
                        data: {!! json_encode($v_tr) !!},
                        borderColor: 'rgba(255, 206, 86, 1)',
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Tegangan Line-to-Line'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Volt (V)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Waktu'
                        }
                    }
                }
            }
        });

        // Untuk Grafik Arus
        const ctxArus = document.getElementById('chartArus').getContext('2d');
        const chartArus = new Chart(ctxArus, {
            type: 'line',
            data: {
                labels: {!! json_encode($realtimeLabels) !!},
                datasets: [
                    {
                        label: 'Arus R',
                        data: {!! json_encode($arus_r) !!},
                        borderColor: 'rgba(255, 99, 132, 1)',
                        tension: 0.2,
                        fill: false
                    },
                    {
                        label: 'Arus S',
                        data: {!! json_encode($arus_s) !!},
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.2,
                        fill: false
                    },
                    {
                        label: 'Arus T',
                        data: {!! json_encode($arus_t) !!},
                        borderColor: 'rgba(153, 102, 255, 1)',
                        tension: 0.2,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Arus R/S/T'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Ampere (A)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Waktu'
                        }
                    }
                }
            }
        });

        // Untuk Grafik Daya
        const ctxDaya = document.getElementById('chartDaya').getContext('2d');
        const chartDaya = new Chart(ctxDaya, {
            type: 'line',
            data: {
                labels: {!! json_encode($realtimeLabels) !!},
                datasets: [
                    {
                        label: 'Daya R',
                        data: {!! json_encode($daya_r) !!},
                        borderColor: 'rgba(255, 99, 132, 1)', // Merah
                        tension: 0.2,
                        fill: false
                    },
                    {
                        label: 'Daya S',
                        data: {!! json_encode($daya_s) !!},
                        borderColor: 'rgba(75, 192, 192, 1)', // Hijau/Cyan
                        tension: 0.2,
                        fill: false
                    },
                    {
                        label: 'Daya T',
                        data: {!! json_encode($daya_t) !!},
                        borderColor: 'rgba(54, 162, 235, 1)', // Biru
                        tension: 0.2,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Daya Aktif R/S/T'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Daya (Watt)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Waktu'
                        }
                    }
                }
            }
        });

        // Untuk Grafik Energi
        const ctxEnergi = document.getElementById('chartEnergi').getContext('2d');
        new Chart(ctxEnergi, {
            type: 'bar',
            data: {
                labels: {!! json_encode($intervalLabels) !!},
                datasets: [
                    {
                        label: 'Energi R (kWh)',
                        data: {!! json_encode($energi_r) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.6)'
                    },
                    {
                        label: 'Energi S (kWh)',
                        data: {!! json_encode($energi_s) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    },
                    {
                        label: 'Energi T (kWh)',
                        data: {!! json_encode($energi_t) !!},
                        backgroundColor: 'rgba(255, 206, 86, 0.6)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Konsumsi Energi per Fasa'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Waktu'
                        }
                    }
                }
            }
        });

        // Untuk Grafik Frekuensi
        const ctxFreq = document.getElementById('chartFrekuensi').getContext('2d');
        new Chart(ctxFreq, {
            type: 'line',
            data: {
                labels: {!! json_encode($intervalLabels) !!},
                datasets: [
                    {
                    label: 'Frekuensi R',
                    data: {!! json_encode($frekuensi_r) !!},
                    borderColor: 'rgba(132, 99, 255, 0.5)',
                    tension: 0.3,
                    fill: false
                    },
                    {
                    label: 'Frekuensi S',
                    data: {!! json_encode($frekuensi_s) !!},
                    borderColor: 'rgba(99, 255, 132, 0.5)',
                    tension: 0.3,
                    fill: false
                    },
                    {
                    label: 'Frekuensi T',
                    data: {!! json_encode($frekuensi_t) !!},
                    borderColor: 'rgba(255, 132, 99, 0.5)',
                    tension: 0.3,
                    fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Frekuensi'
                    }
                },
                scales: {
                    y: {
                        suggestedMin: 49.5,
                        suggestedMax: 50.5
                    }
                }
            }
        });

        // Untuk Grafik Faktor Daya
        const ctxCosphi = document.getElementById('chartCosphi').getContext('2d');
        new Chart(ctxCosphi, {
            type: 'line',
            data: {
                labels: {!! json_encode($intervalLabels) !!},
                datasets: [
                    {
                        label: 'Cosphi R',
                        data: {!! json_encode($faktor_daya_r) !!},
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'Cosphi S',
                        data: {!! json_encode($faktor_daya_s) !!},
                        borderColor: 'rgba(255, 99, 132, 1)',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'Cosphi T',
                        data: {!! json_encode($faktor_daya_t) !!},
                        borderColor: 'rgba(54, 162, 235, 1)',
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Cosphi (Power Factor) per Fasa'
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 1,
                        title: {
                            display: true,
                            text: 'Nilai Cosphi'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>