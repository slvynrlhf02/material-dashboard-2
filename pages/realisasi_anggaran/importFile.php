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
                $tanggal = $row[0];
                $kategori_anggaran = $row[1];
                
                // Hapus koma dan konversi ke integer
                $pagu_anggaran = str_replace(',', '', $row[2]);
                $pagu_anggaran = (int)$pagu_anggaran;
                
                $realisasi_anggaran = str_replace(',', '', $row[3]);
                $realisasi_anggaran = (int)$realisasi_anggaran;

                // Sesuaikan nama kolom dengan yang ada di tabel
                $realisasi_anggaranQuery = "INSERT IGNORE INTO realisasi_anggaran (tanggal, kategori_anggaran, pagu_anggaran, realisasi_anggaran) VALUES ('$tanggal', '$kategori_anggaran', '$pagu_anggaran', '$realisasi_anggaran')";
                $result = mysqli_query($koneksi, $realisasi_anggaranQuery);
                if (!$result) {
                    $_SESSION['message'] = "Error: " . mysqli_error($koneksi);
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
