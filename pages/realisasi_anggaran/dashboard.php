<?php
include_once "realisasi_anggaran.php";
include_once "../../functions.php";
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
  <!-- Fonts and icons -->
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

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="g-sidenav-show bg-gray-200">
  <?php include_once "../../layout/sidebar.php" ?>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <?php 
    if (file_exists("../../layout/navbar.php")) {
        include_once "../../layout/navbar.php";
    } else {
        echo "<div class='alert alert-warning'>Navbar file not found.</div>";
    }
    ?>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
      <div class="row mt-4">
        <div class="col-lg-12 col-md-12 mt-4 mb-4">
          <div class="card z-index-2">
            <div class="card-header p-3 pt-2">
              <div class="text-start pt-1">
                <p class="text-sm mb-0 text-capitalize">Realisasi Anggaran</p>
              </div>
            </div>
            <div class="card-body">
              <canvas id="lineChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <footer class="footer py-4">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>, made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a> for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <!-- Core JS Files -->
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/chartjs.min.js"></script>

  <!-- Script untuk Line Chart -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Data JSON dari file yang sudah di-upload
      var data = {
        "bulan": ["2024-01", "2024-02", "2024-03", "2024-04", "2024-05", "2024-06", "2024-07", "2024-08"],
        "pagu_anggaran": ["1618567239", "1759976424", "1892584194", "2003777782", "2070407527", "2158124585", "2255927043", "2347235606"],
        "realisasi_anggaran": ["1502885771", "1638384976", "1768926933", "1935244589", "2002197675", "2071853102", "2110593799", "2207657650"]
      };

      // Konversi string ke integer
      var pagu_anggaran = data.pagu_anggaran.map(Number);
      var realisasi_anggaran = data.realisasi_anggaran.map(Number);

      var ctx = document.getElementById('lineChart').getContext('2d');
      var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.bulan,
          datasets: [
            {
              label: "Pagu Anggaran",
              borderColor: "rgba(75, 192, 192, 1)",
              backgroundColor: "rgba(75, 192, 192, 0.2)",
              data: pagu_anggaran,
              fill: true,
              pointBackgroundColor: "rgba(75, 192, 192, 1)",
              pointBorderColor: "#fff",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(75, 192, 192, 1)"
            },
            {
              label: "Realisasi Anggaran",
              borderColor: "rgba(153, 102, 255, 1)",
              backgroundColor: "rgba(153, 102, 255, 0.2)",
              data: realisasi_anggaran,
              fill: true,
              pointBackgroundColor: "rgba(153, 102, 255, 1)",
              pointBorderColor: "#fff",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(153, 102, 255, 1)"
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Realisasi Anggaran Bulanan'
            }
          },
          elements: {
            line: {
              tension: 0.4
            },
            point: {
              radius: 5,
              hitRadius: 10,
              hoverRadius: 7
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return 'Rp ' + value.toLocaleString();
                }
              }
            }
          }
        }
      });
    });
  </script>
</body>
</html>
