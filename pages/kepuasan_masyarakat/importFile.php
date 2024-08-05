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

        // Function to convert various date formats to 'Y-m-d'

        function mapValue($value, $mapping)
    {
      $trimmedValue = trim($value);
      return $mapping[$trimmedValue] ?? 0; // Use 0 or another appropriate default value
    }

        function convertToSqlDate($dateString) {
            $formats = ['Y/m/d', 'd/m/Y', 'Y-m-d', 'd-m-Y'];

            foreach ($formats as $format) {
                $date = DateTime::createFromFormat($format, $dateString);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            }
            // If none of the formats match, handle the error
            return null;
        }

        $count = 0;
        foreach ($data as $row) {
            if ($count > 0) {
                $tanggal_survei = $row[0];
                $unit_layanan = $row[1];
                $jenis_kelamin = $row[2];
                $usia_responden = (int)$row[3];
                $pendidikan_responden = $row[4];
                $pekerjaan_responden = $row[5];

                $kesesuaian_persyaratan = mapValue($row[6], [
                    'Tidak sesuai' => 1,
                    'Kurang sesuai' => 2,
                    'Sesuai' => 3,
                    'Sangat sesuai' => 4
                ]);

                $kemudahan_prosedur = mapValue($row[7], [
                    'Tidak mudah' => 1,
                    'Kurang mudah' => 2,
                    'Mudah' => 3,
                    'Sangat mudah' => 4
                ]);

                $kecepatan_pelayanan = mapValue($row[8], [
                    'Tidak cepat' => 1,
                    'Kurang cepat' => 2,
                    'Cepat' => 3,
                    'Sangat cepat' => 4
                ]);

                $kewajaran_biaya = mapValue($row[9], [
                    'Sangat mahal' => 1,
                    'Cukup mahal' => 2,
                    'Murah' => 3,
                    'Gratis' => 4
                ]);

                $kesesuaian_produk = mapValue($row[10], [
                    'Tidak sesuai' => 1,
                    'Kurang sesuai' => 2,
                    'Sesuai' => 3,
                    'Sangat sesuai' => 4
                ]);

                $kompetensi_petugas = mapValue($row[11], [
                    'Tidak kompeten' => 1,
                    'Kurang kompeten' => 2,
                    'Kompeten' => 3,
                    'Sangat kompeten' => 4
                ]);

                $perilaku_petugas = mapValue($row[12], [
                    'Tidak Sopan dan Ramah' => 1,
                    'Kurang Sopan dan Ramah' => 2,
                    'Sopan dan Ramah' => 3,
                    'Sangat Sopan dan Ramah' => 4
                ]);

                $kualitas_sarana = mapValue($row[13], [
                    'Tidak dikelola dengan baik' => 1,
                    'Ada tetapi tidak berfungsi' => 2,
                    'Berfungsi kurang maksimal' => 3,
                    'Dikelola dengan baik' => 4
                ]);

                $penanganan_pengaduan = mapValue($row[14], [
                    'Buruk' => 1,
                    'Cukup' => 2,
                    'Baik' => 3,
                    'Sangat baik' => 4
                ]);

                $tanggal_survei_sql = convertToSqlDate($tanggal_survei);
                if ($tanggal_survei_sql === null) {
                    // Handle the error - for example, log an error message or set a default date value
                    echo "Error: Invalid date format in '$tanggal_survei'";
                    continue; // Skip this row
                }

                $kepuasan_masyarakatQuery = "INSERT IGNORE INTO kepuasan_masyarakat (tanggal_survei, unit_layanan, jenis_kelamin, usia_responden, pendidikan_responden, pekerjaan_responden, kesesuaian_persyaratan, kemudahan_prosedur, kecepatan_pelayanan, kewajaran_biaya, kesesuaian_produk, kompetensi_petugas, perilaku_petugas, kualitas_sarana, penanganan_pengaduan) VALUES ('$tanggal_survei', '$unit_layanan', '$jenis_kelamin', '$usia_responden', '$pendidikan_responden', '$pekerjaan_responden', '$kesesuaian_persyaratan', '$kemudahan_prosedur', '$kecepatan_pelayanan', '$kewajaran_biaya', '$kesesuaian_produk', '$kompetensi_petugas', '$perilaku_petugas', '$kualitas_sarana', '$penanganan_pengaduan')";

                mysqli_query($koneksi, $kepuasan_masyarakatQuery);
            }
            $count++;
        }
        $_SESSION['message'] = 'Data berhasil diunggah!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Format file tidak valid. Hanya menerima file dengan format xls, csv, atau xlsx.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: dashboard.php');
}
