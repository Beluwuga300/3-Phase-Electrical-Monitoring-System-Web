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
        <option value="300">Per 5 Menit</option>
        <option value="1800" selected>Per 30 Menit</option>
        <option value="3600">Per Jam</option>
    </select>
    <h4 class="text-lg font-bold mt-6">Penggunaan Energi</h4>
    <canvas id="chartEnergi" height="100"></canvas>
    <canvas id="chartFrekuensi" height="80"></canvas>
    <canvas id="chartCosphi" height="100"></canvas>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let lastTimestamp = ''; // untuk menyimpan timestamp terakhir yang digunakan
        // Untuk Grafik Tegangan Line-to-Neutral
        const chartL2N = new Chart(document.getElementById('chartL2N'), {
            type: 'line',
            data: {
                labels: {!! json_encode($realtimeLabels) !!},
                datasets: [
                    {
                        label: 'Tegangan R',
                        data: {!! json_encode($tegangan_r) !!},
                        borderColor: 'red'
                    },
                    {
                        label: 'Tegangan S',
                        data: {!! json_encode($tegangan_s) !!},
                        borderColor: 'green'
                    },
                    {
                        label: 'Tegangan T',
                        data: {!! json_encode($tegangan_t) !!},
                        borderColor: 'blue' 
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
        const chartVLL = new Chart(document.getElementById('chartVLL'), {
            type: 'line',
            data: {
                labels: {!! json_encode($realtimeLabels) !!},
                datasets: [
                    {
                        label: 'Tegangan RS',
                        data: {!! json_encode($v_rs) !!},
                        borderColor: '#f26c0c'
                    },
                    {
                        label: 'Tegangan ST',
                        data: {!! json_encode($v_st) !!},
                        borderColor: '#33f81d'
                    },
                    {
                        label: 'Tegangan RT',
                        data: {!! json_encode($v_tr) !!},
                        borderColor: '#f81d5f'
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
        const chartArus = new Chart(document.getElementById('chartArus'), {
            type: 'line',
            data: {
                labels: {!! json_encode($realtimeLabels) !!},
                datasets: [
                    {
                        label: 'Arus R',
                        data: {!! json_encode($arus_r) !!},
                        borderColor: '#8d1191'
                    },
                    {
                        label: 'Arus S',
                        data: {!! json_encode($arus_s) !!},
                        borderColor: '#0c8d11'
                    },
                    {
                        label: 'Arus T',
                        data: {!! json_encode($arus_t) !!},
                        borderColor: '#11918d'
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

        // Untuk Grafik Daya Aktif
        const chartDaya = new Chart(document.getElementById('chartDaya'), {
            type: 'line',
            data: {
                labels: {!! json_encode($realtimeLabels) !!},
                datasets: [
                    {
                        label: 'Daya R',
                        data: {!! json_encode($daya_r) !!},
                        borderColor: '#11d4d0'
                    },
                    {
                        label: 'Daya S',
                        data: {!! json_encode($daya_s) !!},
                        borderColor: '#d01111'
                    },
                    {
                        label: 'Daya T',
                        data: {!! json_encode($daya_t) !!},
                        borderColor: '#d0d011'
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
        const chartEnergi = new Chart(document.getElementById('chartEnergi'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($intervalLabels) !!},
                datasets: [
                    {
                        label: 'Energi R',
                        data: {!! json_encode($energi_r) !!},
                        backgroundColor: 'red'
                    },
                    {
                        label: 'Energi S',
                        data: {!! json_encode($energi_s) !!},
                        backgroundColor: 'green'
                    },
                    {
                        label: 'Energi T',
                        data: {!! json_encode($energi_t) !!},
                        backgroundColor: 'blue'
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
        const chartFrekuensi = new Chart(document.getElementById('chartFrekuensi'), {
            type: 'line',
            data: {
                labels: {!! json_encode($intervalLabels) !!},
                datasets: [
                    {
                        label: 'Frekuensi R',
                        data: {!! json_encode($frekuensi_r) !!},
                        borderColor: '#a30ee3',
                        fill: false
                    },
                    {
                        label: 'Frekuensi S',
                        data: {!! json_encode($frekuensi_s) !!},
                        borderColor: '#9ef229',
                        fill: false
                    },
                    {
                        label: 'Frekuensi T',
                        data: {!! json_encode($frekuensi_t) !!},
                        borderColor: '#2998f2',
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
                        suggestedMax: 50.5,
                        title: {
                            display: true,
                            text: 'Frekuensi (Hz)'
                        }
                    }
                }
            }
        });

        // Untuk Grafik Faktor Daya
        const chartCosphi = new Chart(document.getElementById('chartCosphi'), {
            type: 'line',
            data: {
                labels: {!! json_encode($intervalLabels) !!},
                datasets: [
                    {
                        label: 'Faktor Daya R',
                        data: {!! json_encode($faktor_daya_r) !!},
                        borderColor: '#ff6384',
                        fill: false
                    },
                    {
                        label: 'Faktor Daya S',
                        data: {!! json_encode($faktor_daya_s) !!},
                        borderColor: '#36a2eb',
                        fill: false
                    },
                    {
                        label: 'Faktor Daya T',
                        data: {!! json_encode($faktor_daya_t) !!},
                        borderColor: '#cc65fe',
                        fill: false
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

        async function updateCharts() {
            // Mengambil data awal dari server
            try {
                const response = await fetch(`{{ route('data.update') }}?last_timestamp=${lastTimestamp}`);
                const responseData = await response.json();

                if (responseData.newData && responseData.newData.length > 0) {
                    responseData.newData.forEach(dataPoint => {
                        const newLabel = new Date(dataPoint.waktu).toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                        const chartsToUpdate = [
                            chartL2N, chartVLL, chartArus, chartDaya
                        ];
                        if (chartsToUpdate[0].data.labels.length >= 20) {
                            chartsToUpdate.forEach(chart => {
                                chart.data.labels.shift(); // Hapus label pertama
                                chart.data.datasets.forEach(dataset => {
                                    dataset.data.shift(); // Hapus data pertama untuk setiap dataset
                                });
                            });
                        }
                        chartL2N.data.labels.push(newLabel);
                        chartL2N.data.datasets[0].data.push(dataPoint.tegangan_r);
                        chartL2N.data.datasets[1].data.push(dataPoint.tegangan_s);
                        chartL2N.data.datasets[2].data.push(dataPoint.tegangan_t);

                        chartVLL.data.labels.push(newLabel);
                        chartVLL.data.datasets[0].data.push(dataPoint.tegangan_rs);
                        chartVLL.data.datasets[1].data.push(dataPoint.tegangan_st);
                        chartVLL.data.datasets[2].data.push(dataPoint.tegangan_tr);

                        chartArus.data.labels.push(newLabel);
                        chartArus.data.datasets[0].data.push(dataPoint.arus_r);
                        chartArus.data.datasets[1].data.push(dataPoint.arus_s);
                        chartArus.data.datasets[2].data.push(dataPoint.arus_t);

                        chartDaya.data.labels.push(newLabel);
                        chartDaya.data.datasets[0].data.push(dataPoint.daya_r);
                        chartDaya.data.datasets[1].data.push(dataPoint.daya_s);
                        chartDaya.data.datasets[2].data.push(dataPoint.daya_t);
                    });
                    lastTimestamp = responseData.newData[responseData.newData.length - 1].waktu; // Update timestamp terakhir

                    //update chart real-time
                    chartL2N.update('none'); //update tanpa animasi
                    chartVLL.update('none');
                    chartArus.update('none');
                    chartDaya.update('none');
                }
            } catch (error) {
                console.error('Gagal mengambil data update:', error);
            }
        }
        // 1. memanggil saat halam selesai dimuat untuk mengisi data awal
        document.addEventListener('DOMContentLoaded', updateCharts);

        // 2. set interval untuk mengecek data baru setiap 2 detuk
        setInterval(updateCharts, 2000);

        // 3. saat dropdown diubah, reload halaaman
        document.getElementById('intervalSelect').addEventListener('change', function() {
            const selectedInterval = this.value;
            // reload halaman dengan parameter interval yang dipilih
            window.location.href = `{{ route('dashboard') }}?interval=${selectedInterval}`;
        });
    </script>
</body>
</html>