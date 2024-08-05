
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
    color: #055F05; /* Green color */
}

.negative-change {
    color: #A93840; /* Red color */
}

.positive-change {
    color: #055F05; /* Green color */
}

  </style>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <?php include_once "../../layout/sidebar.php" ?>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid  px-3">
        <nav aria-label="breadcrumb">
          <form method="GET" action="">
            <select name="tahun" onchange="this.form.submit()">
              <option value="">Pilih Tahun</option>
              <?php foreach ($years as $year): ?>
                <option value="<?php echo $year; ?>" <?php echo $year == $selectedYear ? 'selected' : ''; ?>>
                  <?php echo $year; ?>
                </option>
              <?php endforeach; ?>
            </select>

            <?php if ($selectedYear): ?>
              <select name="bulan" onchange="this.form.submit()">
                <option value="">Pilih Bulan</option>
                <?php foreach ($months as $month): ?>
                  <option value="<?php echo $month; ?>" <?php echo $month == $selectedMonth ? 'selected' : ''; ?>>
                    <?php echo $month; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            <?php endif; ?>
          </form>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
              Import 
            </button>

            <!-- Modal -->
            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="importFile.php" method="POST" enctype="multipart/form-data">
                      <div class="form-group mb-3">
                        <label for="import_file" class="form-label">Choose File</label>
                        <input type="file" name="import_file" id="import_file" class="form-control" />
                      </div>
                      <button type="submit" name="save_excel_data" class="btn btn-primary">Upload</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <!-- Tombol -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
              Import 
            </button>

            <!-- Modal -->
            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="importFile.php" method="POST" enctype="multipart/form-data">
                      <div class="form-group mb-3">
                        <label for="import_file" class="form-label">Choose File</label>
                        <input type="file" name="import_file" id="import_file" class="form-control" />
                      </div>
                      <button type="submit" name="save_excel_data" class="btn btn-primary">Submit</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->

    <div class="container-fluid ">



    <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <p class="small-text" style="font-size: .8rem;">Nilai Interval Pelayanan, Gedung CTP 2023</p>
                <div class="d-flex align-items-baseline justify-content-start">
                    <?php
                    // Menghitung IKM untuk Gedung CTP tahun 2023
                    $ikm_tahun_ini = hitungIKM(2023, 'Gedung CTP');
                    ?>
                    <h1 class="m-0 positive-value"><?php echo number_format($ikm_tahun_ini, 2); ?></h1>
                </div>
                <p class="small-text">Indeks Kepuasan Masyarakat (IKM) Gedung Cimahi Techno Park (CTP)</p>
                <div class="d-flex align-items-baseline justify-content-start">
                    <?php
                    // Menghitung perubahan IKM untuk Gedung CTP
                    $perubahan_ctp = hitungPerubahanIKM('Gedung CTP');
                    $class = $perubahan_ctp < 0 ? 'negative-change' : 'positive-change';
                    ?>
                    <span class="<?php echo $class; ?> text-sm font-weight-bolder">
                        <?php echo ($perubahan_ctp < 0) ? '↓' : '↑'; ?> <?php echo number_format(abs($perubahan_ctp), 2); ?>% dari tahun sebelumnya
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <p class="small-text" style="font-size: .8rem;">IKM Cimahi Techno Park</p>
                <div class="d-flex align-items-baseline justify-content-start">
                    <?php
                    // Menghitung IKM untuk Gedung Cimahi Techno Park tahun 2023
                    $ikm_tahun_ini = hitungIKM(2023, 'Gedung Cimahi Techno Park');
                    ?>
                    <h1 class="m-0 positive-value"><?php echo number_format($ikm_tahun_ini, 2); ?></h1>
                </div>
                <p class="small-text">Indeks Kepuasan Masyarakat (IKM) Gedung Cimahi Techno Park</p>
                <div class="d-flex align-items-baseline justify-content-start">
                    <?php
                    // Menghitung perubahan IKM untuk Gedung Cimahi Techno Park
                    $perubahan_ctp = hitungPerubahanIKM('Gedung Cimahi Techno Park');
                    $class = $perubahan_ctp < 0 ? 'negative-change' : 'positive-change';
                    ?>
                    <span class="<?php echo $class; ?> text-sm font-weight-bolder">
                        <?php echo ($perubahan_ctp < 0) ? '↓' : '↑'; ?> <?php echo number_format(abs($perubahan_ctp), 2); ?>% dari tahun sebelumnya
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <p class="small-text" style="font-size: .8rem;">IKM Baros Information Technology Center</p>
                <div class="d-flex align-items-baseline justify-content-start">
                    <?php
                    // Menghitung IKM untuk Gedung Baros Information Technology Center tahun 2023
                    $ikm_tahun_ini = hitungIKM(2023, 'Gedung Baros Information Technology Center');
                    ?>
                    <h1 class="m-0 positive-value"><?php echo number_format($ikm_tahun_ini, 2); ?></h1>
                </div>
                <p class="small-text">Indeks Kepuasan Masyarakat (IKM) Baros Information Technology Center</p>
                <div class="d-flex align-items-baseline justify-content-start">
                    <?php
                    // Menghitung perubahan IKM untuk Gedung Baros Information Technology Center
                    $perubahan_bitc = hitungPerubahanIKM('Gedung Baros Information Technology Center');
                    $class = $perubahan_bitc < 0 ? 'negative-change' : 'positive-change';
                    ?>
                    <span class="<?php echo $class; ?> text-sm font-weight-bolder">
                        <?php echo ($perubahan_bitc < 0) ? '↓' : '↑'; ?> <?php echo number_format(abs($perubahan_bitc), 2); ?>% dari tahun sebelumnya
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


        <div class="row">
          <div class="col-lg-12 col-md-12 mt-4 mb-4">
            <div class="card z-index-2 ">
              <div class="card-body">
                <div id="combinedChart"></div>
              </div>
            </div>
          </div>
        </div>

        <footer class="footer py-4  ">
          <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
              <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                  © <script>
                    document.write(new Date().getFullYear())
                  </script>,
                  made with <i class="fa fa-heart"></i> by
                  <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                  for a better web.
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
    </div>

    <!--   Core JS Files   -->
    <script src="../../assets/js/core/popper.min.js"></script>
    <script src="../../assets/js/core/bootstrap.min.js"></script>
    <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../../assets/js/plugins/chartjs.min.js"></script>
    <script src="../../assets/js/material-dashboard.min.js?v=3.0.0"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataBITC = <?php echo json_encode(array_values($averageData['Gedung Baros Information Technology Center'])); ?>;
        const dataCTP = <?php echo json_encode(array_values($averageData['Gedung Cimahi Techno Park'])); ?>;
        const labels = <?php echo json_encode(array_keys($averageData['Gedung Baros Information Technology Center'])); ?>;

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
  </main>
</body>

</html>
