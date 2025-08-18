/* global Chart, iziToast */

(function () {
  const cfg = window.DASHBOARD_CONFIG || {};
  const {
    lastTimestamp: initialTimestamp,
    routes = {},
    data = {},
  } = cfg;

  // Pastikan pustaka global dari CDN bisa diakses dalam konteks ESM (@vite)
  const Chart = (typeof globalThis !== 'undefined' ? globalThis.Chart : undefined) || (typeof window !== 'undefined' ? window.Chart : undefined);
  const iziToast = (typeof globalThis !== 'undefined' ? globalThis.iziToast : undefined) || (typeof window !== 'undefined' ? window.iziToast : undefined);

  // Gunakan UNIX seconds secara konsisten untuk komunikasi dengan backend
  let lastTimestamp = initialTimestamp ? parseInt(initialTimestamp, 10) : null;

  // --- Konfigurasi Alert Tegangan ---
  const minVoltage = 200; // batas bawah tegangan normal (Volt)
  const maxVoltage = 240; // batas atas tegangan normal (Volt)
  let alertActive = false; // status apakah toast sedang tampil
  let toastAlert = null;
  let currentAlertType = null;

  function showVoltageAlert(type = 'out') {
    if (alertActive) {
      if (type === currentAlertType) {
        return; // toast sama sudah tampil
      }
      // Jenis alert berubah, sembunyikan toast lama
      if (toastAlert) {
        iziToast.hide(toastAlert);
      }
    }
    alertActive = true;
    currentAlertType = type;
    const options = {
      position: 'topRight',
      close: true,
      timeout: 3000,
      onClosed: function () {
        alertActive = false;
        toastAlert = null;
        currentAlertType = null;
      },
    };
    if (type === 'undervolt') {
      options.title = 'Undervoltage';
      options.message = 'Tegangan terlalu rendah!';
      toastAlert = iziToast.warning(options);
    } else if (type === 'overvolt') {
      options.title = 'Overvoltage';
      options.message = 'Tegangan terlalu tinggi!';
      toastAlert = iziToast.error(options);
    } else {
      options.title = 'Peringatan';
      options.message = 'Tegangan di luar batas normal.';
      toastAlert = iziToast.warning(options);
    }
  }

  function hideVoltageAlert() {
    if (!alertActive) return;
    if (toastAlert) {
      iziToast.hide(toastAlert);
    }
    alertActive = false;
    toastAlert = null;
    currentAlertType = null;
  }

  // Init charts with server-provided data
  const chartL2N = new Chart(document.getElementById('chartL2N'), {
    type: 'line',
    data: {
      labels: data.realtimeLabels || [],
      datasets: [
        { label: 'Tegangan R', data: data.tegangan_r || [], borderColor: 'red' },
        { label: 'Tegangan S', data: data.tegangan_s || [], borderColor: 'green' },
        { label: 'Tegangan T', data: data.tegangan_t || [], borderColor: 'blue' },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Tegangan (V)' } },
        x: { title: { display: true, text: 'Waktu' } },
      },
    },
  });

  const chartVLL = new Chart(document.getElementById('chartVLL'), {
    type: 'line',
    data: {
      labels: data.realtimeLabels || [],
      datasets: [
        { label: 'Tegangan RS', data: data.v_rs || [], borderColor: '#f26c0c' },
        { label: 'Tegangan ST', data: data.v_st || [], borderColor: '#33f81d' },
        { label: 'Tegangan RT', data: data.v_tr || [], borderColor: '#f81d5f' },
      ],
    },
    options: {
      responsive: true,
      plugins: { title: { display: true, text: 'Grafik Tegangan Line-to-Line' } },
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Volt (V)' } },
        x: { title: { display: true, text: 'Waktu' } },
      },
    },
  });

  const chartArus = new Chart(document.getElementById('chartArus'), {
    type: 'line',
    data: {
      labels: data.realtimeLabels || [],
      datasets: [
        { label: 'Arus R', data: data.arus_r || [], borderColor: '#8d1191' },
        { label: 'Arus S', data: data.arus_s || [], borderColor: '#0c8d11' },
        { label: 'Arus T', data: data.arus_t || [], borderColor: '#11918d' },
      ],
    },
    options: {
      responsive: true,
      plugins: { title: { display: true, text: 'Grafik Arus R/S/T' } },
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Ampere (A)' } },
        x: { title: { display: true, text: 'Waktu' } },
      },
    },
  });

  const chartDaya = new Chart(document.getElementById('chartDaya'), {
    type: 'line',
    data: {
      labels: data.realtimeLabels || [],
      datasets: [
        { label: 'Daya R', data: data.daya_r || [], borderColor: '#11d4d0' },
        { label: 'Daya S', data: data.daya_s || [], borderColor: '#d01111' },
        { label: 'Daya T', data: data.daya_t || [], borderColor: '#d0d011' },
      ],
    },
    options: {
      responsive: true,
      plugins: { title: { display: true, text: 'Grafik Daya Aktif R/S/T' } },
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Daya (Watt)' } },
        x: { title: { display: true, text: 'Waktu' } },
      },
    },
  });

  const chartEnergi = new Chart(document.getElementById('chartEnergi'), {
    type: 'bar',
    data: {
      labels: data.intervalLabels || [],
      datasets: [
        { label: 'Energi R', data: data.energi_r || [], backgroundColor: 'red' },
        { label: 'Energi S', data: data.energi_s || [], backgroundColor: 'green' },
        { label: 'Energi T', data: data.energi_t || [], backgroundColor: 'blue' },
      ],
    },
    options: {
      responsive: true,
      plugins: { title: { display: true, text: 'Grafik Konsumsi Energi per Fasa' } },
      scales: {
        y: { beginAtZero: true },
        x: { title: { display: true, text: 'Waktu' } },
      },
    },
  });

  const chartFrekuensi = new Chart(document.getElementById('chartFrekuensi'), {
    type: 'line',
    data: {
      labels: data.intervalLabels || [],
      datasets: [
        { label: 'Frekuensi R', data: data.frekuensi_r || [], borderColor: '#a30ee3', fill: false },
        { label: 'Frekuensi S', data: data.frekuensi_s || [], borderColor: '#9ef229', fill: false },
        { label: 'Frekuensi T', data: data.frekuensi_t || [], borderColor: '#2998f2', fill: false },
      ],
    },
    options: {
      responsive: true,
      plugins: { title: { display: true, text: 'Grafik Frekuensi' } },
      scales: {
        y: {
          suggestedMin: 49.5,
          suggestedMax: 50.5,
          title: { display: true, text: 'Frekuensi (Hz)' },
        },
      },
    },
  });

  const chartCosphi = new Chart(document.getElementById('chartCosphi'), {
    type: 'line',
    data: {
      labels: data.intervalLabels || [],
      datasets: [
        { label: 'Faktor Daya R', data: data.faktor_daya_r || [], borderColor: '#ff6384', fill: false },
        { label: 'Faktor Daya S', data: data.faktor_daya_s || [], borderColor: '#36a2eb', fill: false },
        { label: 'Faktor Daya T', data: data.faktor_daya_t || [], borderColor: '#cc65fe', fill: false },
      ],
    },
    options: {
      responsive: true,
      plugins: { title: { display: true, text: 'Grafik Cosphi (Power Factor) per Fasa' } },
      scales: {
        y: { min: 0, max: 1, title: { display: true, text: 'Nilai Cosphi' } },
      },
    },
  });

  async function updateCharts() {
    try {
      const url = lastTimestamp
        ? `${routes.update}?lastTimestamp=${encodeURIComponent(lastTimestamp)}`
        : `${routes.update}`;
      const response = await fetch(url);
      const responseData = await response.json();

      if (responseData.newData && responseData.newData.length > 0) {
        responseData.newData.forEach((dataPoint) => {
          const tsMs = dataPoint.waktu_unix
            ? dataPoint.waktu_unix * 1000
            : Date.parse(dataPoint.waktu_iso || dataPoint.waktu);
          const newLabel = new Date(tsMs).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
          });
          const chartsToUpdate = [chartL2N, chartVLL, chartArus, chartDaya];
          if (chartsToUpdate[0].data.labels.length >= 20) {
            chartsToUpdate.forEach((chart) => {
              chart.data.labels.shift();
              chart.data.datasets.forEach((dataset) => {
                dataset.data.shift();
              });
            });
          }
          chartL2N.data.labels.push(newLabel);
          chartL2N.data.datasets[0].data.push(dataPoint.tegangan_r);
          chartL2N.data.datasets[1].data.push(dataPoint.tegangan_s);
          chartL2N.data.datasets[2].data.push(dataPoint.tegangan_t);

          chartVLL.data.labels.push(newLabel);
          chartVLL.data.datasets[0].data.push(dataPoint.voltage_rs);
          chartVLL.data.datasets[1].data.push(dataPoint.voltage_st);
          chartVLL.data.datasets[2].data.push(dataPoint.voltage_tr);

          chartArus.data.labels.push(newLabel);
          chartArus.data.datasets[0].data.push(dataPoint.arus_r);
          chartArus.data.datasets[1].data.push(dataPoint.arus_s);
          chartArus.data.datasets[2].data.push(dataPoint.arus_t);

          chartDaya.data.labels.push(newLabel);
          chartDaya.data.datasets[0].data.push(dataPoint.daya_r);
          chartDaya.data.datasets[1].data.push(dataPoint.daya_s);
          chartDaya.data.datasets[2].data.push(dataPoint.daya_t);
        });
        const last = responseData.newData[responseData.newData.length - 1];
        // Simpan sebagai UNIX seconds agar konsisten dengan backend
        lastTimestamp =
          (typeof last.waktu_unix === 'number' && !Number.isNaN(last.waktu_unix))
            ? last.waktu_unix
            : Math.floor(new Date(last.waktu_iso || last.waktu).getTime() / 1000);

        chartL2N.update('none');
        chartVLL.update('none');
        chartArus.update('none');
        chartDaya.update('none');

        // --- Cek kondisi tegangan untuk alert ---
        const latest = responseData.newData[responseData.newData.length - 1];
        const voltages = [latest.tegangan_r, latest.tegangan_s, latest.tegangan_t];
        const inRange = voltages.every((v) => v >= minVoltage && v <= maxVoltage);
        if (!inRange) {
          const minV = Math.min(...voltages);
          if (minV < minVoltage) {
            showVoltageAlert('undervolt');
          } else {
            showVoltageAlert('overvolt');
          }
        } else {
          hideVoltageAlert();
        }
      }
    } catch (error) {
      console.error('Gagal mengambil data update:', error);
    }
  }

  document.addEventListener('DOMContentLoaded', updateCharts);
  setInterval(updateCharts, 2000);

  const intervalSelect = document.getElementById('intervalSelect');
  if (intervalSelect) {
    intervalSelect.addEventListener('change', function () {
      const selectedInterval = this.value;
      window.location.href = `${routes.dashboard}?interval=${encodeURIComponent(selectedInterval)}`;
    });
  }
})();
