<?php
session_start();
include_once "koneksi.php";

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['save_excel_data'])) {
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($file_ext, $allowed_ext)) {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach ($data as $row) {
            // Lewati baris pertama jika berisi header
            if ($count > 0) {
                $tanggal_monitoring = $row[0];
                $indikator_kinerja = $row[1];
                $target = $row[2];
                $realisasi = $row[3];

                // Sesuaikan nama kolom dengan yang ada di tabel
                $realisasi_kinerjaQuery = "INSERT IGNORE realisasi_kinerja (tanggal_monitoring, indikator_kinerja, target, realisasi) VALUES ('$tanggal_monitoring', '$indikator_kinerja', '$target', '$realisasi')";
                $result = mysqli_query($koneksi, $realisasi_kinerjaQuery);
                if (!$result) {
                    $_SESSION['message'] = "Error: " . mysqli_error($db);
                    header('Location: index.php');
                    exit(0);
                }
            } else {
                $count = 1;
            }
        }

        $_SESSION['message'] = "Successfully Imported";
        header('Location: dashboard.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Invalid File";
        header('Location: dashboard.php');
        exit(0);
    }
}
