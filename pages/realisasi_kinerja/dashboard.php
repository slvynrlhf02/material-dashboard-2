<?php
include_once "kepuasan_masyarakat.php";
include_once "../../functions.php";

// Ambil data tahun dan bulan
$years = getUniqueYears();
$selectedYear = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
$months = getUniqueMonths($selectedYear);
$selectedMonth = isset($_GET['bulan']) ? intval($_GET['bulan']) : date('n');

// Filter data berdasarkan tahun dan bulan yang dipilih
$ikm_bitc = hitungIKMByYearMonth($selectedYear, $selectedMonth, 'Gedung Baros Information Technology Center');
$ikm_ctp = hitungIKMByYearMonth($selectedYear, $selectedMonth, 'Gedung Cimahi Techno Park');
$perubahan_bitc = hitungPerubahanIKM('Gedung Baros Information Technology Center');
$perubahan_ctp = hitungPerubahanIKM('Gedung Cimahi Techno Park');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Material Dashboard 2 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>



  <style>
    .progress-text {
      font-size: 4rem;
      font-weight: bold;
      margin: 0;
    }

    .small-text {
      font-size: 1rem;
      color: gray;
      margin: 0;
    }

    .status-text {
      font-size: .8rem;
      color: gray;
      margin: 0;
    }

    .positive-value {
    color: #4caf50;
  }

  .negative-value {
    color: #f44336;
  }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-lg-12 col-md-12 mb-4">
        <div class="card">
          <div class="card-header pb-0">
            <div class="row">
              <div class="col-lg-6 col-7">
                <h6>Indeks Kepuasan Masyarakat</h6>
              </div>
              <div class="col-lg-6 col-5 text-end">
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Pilih Tahun
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php foreach ($years as $year) : ?>
                    <li><a class="dropdown-item" href="?tahun=<?= $year ?>"><?= $year ?></a></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                    Pilih Bulan
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                    <?php foreach ($months as $month) : ?>
                    <li><a class="dropdown-item" href="?tahun=<?= $selectedYear ?>&bulan=<?= $month ?>"><?= date('F', mktime(0, 0, 0, $month, 10)) ?></a></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body p-3">
            <div id="combinedChart"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- BITC -->
      <div class="col-lg-6 col-md-6 mb-4">
        <div class="card">
          <div class="card-header pb-0">
            <h6>Gedung Baros Information Technology Center</h6>
          </div>
          <div class="card-body p-3">
            <div class="row">
              <div class="col-lg-6 col-md-6">
                <h3 class="progress-text"><?= number_format($ikm_bitc, 2) ?></h3>
                <p class="small-text">IKM</p>
              </div>
              <div class="col-lg-6 col-md-6 text-end">
                <h5 class="progress-text <?= $perubahan_bitc >= 0 ? 'positive-value' : 'negative-value' ?>">
                  <?= number_format($perubahan_bitc, 2) ?>%
                </h5>
                <p class="status-text">Perubahan</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CTP -->
      <div class="col-lg-6 col-md-6 mb-4">
        <div class="card">
          <div class="card-header pb-0">
            <h6>Gedung Cimahi Techno Park</h6>
          </div>
          <div class="card-body p-3">
            <div class="row">
              <div class="col-lg-6 col-md-6">
                <h3 class="progress-text"><?= number_format($ikm_ctp, 2) ?></h3>
                <p class="small-text">IKM</p>
              </div>
              <div class="col-lg-6 col-md-6 text-end">
                <h5 class="progress-text <?= $perubahan_ctp >= 0 ? 'positive-value' : 'negative-value' ?>">
                  <?= number_format($perubahan_ctp, 2) ?>%
                </h5>
                <p class="status-text">Perubahan</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const dataBITC = [<?= $ikm_bitc ?>];
      const dataCTP = [<?= $ikm_ctp ?>];
      const labels = ['IKM'];

      const isDataEmpty = (data) => data.every(item => item === 0 || item === null || item === undefined);

      if (isDataEmpty(dataBITC) && isDataEmpty(dataCTP)) {
        document.getElementById('combinedChart').innerHTML = '<p>Data tidak tersedia untuk ditampilkan.</p>';
      } else {
        var options = {
          series: [
            {
              name: 'Gedung Baros Information Technology Center',
              data: dataBITC
            },
            {
              name: 'Gedung Cimahi Techno Park',
              data: dataCTP
            }
          ],
          chart: {
            type: 'bar',
            height: 350
          },
          colors: ['#808080', '#80CEE1'],
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '55%',
              endingShape: 'rounded',
              dataLabels: {
                position: 'top' // Menampilkan label di atas batang
              }
            }
          },
          dataLabels: {
            enabled: true,
            formatter: function (value) {
              return value.toFixed(2); // 2 angka di belakang koma
            },
            offsetY: -20, // Pindahkan label ke atas batang
            style: {
              fontSize: '12px',
              colors: ["#304758"]
            }
          },
          fill: {
            opacity: 1
          },
          xaxis: {
            categories: labels,
            title: {
              text: 'Bulan'
            }
          },
          yaxis: {
            title: {
              text: 'Nilai'
            },
            labels: {
              formatter: function (value) {
                return value.toFixed(2); // 2 angka di belakang koma
              }
            }
          },
          grid: {
            show: false // Menghapus gridlines
          },
          title: {
            text: 'Perbandingan Layanan',
            align: 'left'
          },
          legend: {
            position: 'top',
            horizontalAlign: 'center',
            floating: true,
            offsetY: -20
          }
        };

        var chart = new ApexCharts(document.querySelector("#combinedChart"), options);
        chart.render();
      }
    });
  </script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>
