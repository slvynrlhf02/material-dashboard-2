<?php

require_once('koneksi.php');

// Fungsi untuk menghitung nilai rata-rata dari sebuah unsur pelayanan berdasarkan unit layanan
// Fungsi untuk mendapatkan daftar tahun unik
function getUniqueYears()
{
    global $koneksi;
    $sql = "SELECT DISTINCT YEAR(tanggal_survei) AS tahun FROM kepuasan_masyarakat ORDER BY tahun DESC";
    $result = $koneksi->query($sql);
    $years = [];
    while ($row = $result->fetch_assoc()) {
        $years[] = $row['tahun'];
    }
    return $years;
}

// Fungsi untuk mendapatkan daftar bulan unik berdasarkan tahun
function getUniqueMonths($tahun)
{
    global $koneksi;
    $sql = "SELECT DISTINCT MONTH(tanggal_survei) AS bulan FROM kepuasan_masyarakat WHERE YEAR(tanggal_survei) = $tahun ORDER BY bulan";
    $result = $koneksi->query($sql);
    $months = [];
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['bulan'];
    }
    return $months;
}

// Fungsi untuk menghitung IKM berdasarkan tahun dan bulan
function hitungIKMByYearMonth($tahun, $bulan, $unit_layanan = null)
{
    global $koneksi;

    $sql = "SELECT kesesuaian_persyaratan, kemudahan_prosedur, kecepatan_pelayanan, kewajaran_biaya, kesesuaian_produk, kompetensi_petugas, perilaku_petugas, kualitas_sarana, penanganan_pengaduan FROM kepuasan_masyarakat WHERE YEAR(tanggal_survei) = $tahun AND MONTH(tanggal_survei) = $bulan";

    if ($unit_layanan) {
        $sql .= " AND unit_layanan = '$unit_layanan'";
    }

    $result = $koneksi->query($sql);
    $total_responden = $result->num_rows;
    $total_nilai_unsur = array_fill(0, 9, 0);

    while ($row = $result->fetch_assoc()) {
        for ($i = 0; $i < 9; $i++) {
            $total_nilai_unsur[$i] += array_values($row)[$i];
        }
    }

    if ($total_responden > 0) {
        $total_nrr_tertinggi = 0;
        foreach ($total_nilai_unsur as $nilai_unsur) {
            $nrr = ($nilai_unsur / $total_responden);
            $total_nrr_tertinggi += $nrr * 0.11; // 0.11 karena terdapat 9 unsur layanan
        }
        return $total_nrr_tertinggi * 25;
    } else {
        return 0;
    }
}

function calculateAverageByUnit($data, $unit, $field)
{
    $total = 0;
    $count = 0;

    foreach ($data as $entry) {
        if ($entry['unit_layanan'] === $unit) {
            $total += $entry[$field];
            $count++;
        }
    }

    return $count ? $total / $count : 0;
}

// Ambil data dari database
$query_data = "SELECT unit_layanan, kesesuaian_persyaratan, kemudahan_prosedur, kecepatan_pelayanan, kewajaran_biaya, kesesuaian_produk, kompetensi_petugas, perilaku_petugas, kualitas_sarana, penanganan_pengaduan FROM kepuasan_masyarakat";
$rs_data = mysqli_query($koneksi, $query_data) or die(mysqli_error($koneksi));

$data = [];
while ($row = mysqli_fetch_assoc($rs_data)) {
    $data[] = $row;
}

// Hitung rata-rata unsur pelayanan per unit layanan
$units = array_unique(array_column($data, 'unit_layanan'));

$averageData = [];
foreach ($units as $unit) {
    $averageData[$unit] = [
        "kesesuaian_persyaratan" => calculateAverageByUnit($data, $unit, "kesesuaian_persyaratan"),
        "kemudahan_prosedur" => calculateAverageByUnit($data, $unit, "kemudahan_prosedur"),
        "kecepatan_pelayanan" => calculateAverageByUnit($data, $unit, "kecepatan_pelayanan"),
        "kewajaran_biaya" => calculateAverageByUnit($data, $unit, "kewajaran_biaya"),
        "kesesuaian_produk" => calculateAverageByUnit($data, $unit, "kesesuaian_produk"),
        "kompetensi_petugas" => calculateAverageByUnit($data, $unit, "kompetensi_petugas"),
        "perilaku_petugas" => calculateAverageByUnit($data, $unit, "perilaku_petugas"),
        "kualitas_sarana" => calculateAverageByUnit($data, $unit, "kualitas_sarana"),
        "penanganan_pengaduan" => calculateAverageByUnit($data, $unit, "penanganan_pengaduan"),
    ];
}

// Fungsi untuk menghitung IKM
function hitungIKM($tahun, $unit_layanan = null)
{
    global $koneksi;

    $sql = "SELECT kesesuaian_persyaratan, kemudahan_prosedur, kecepatan_pelayanan, kewajaran_biaya, kesesuaian_produk, kompetensi_petugas, perilaku_petugas, kualitas_sarana, penanganan_pengaduan FROM kepuasan_masyarakat WHERE YEAR(tanggal_survei) = $tahun";

    if ($unit_layanan) {
        $sql .= " AND unit_layanan = '$unit_layanan'";
    }

    $result = $koneksi->query($sql);
    $total_responden = $result->num_rows;
    $total_nilai_unsur = array_fill(0, 9, 0);

    while ($row = $result->fetch_assoc()) {
        for ($i = 0; $i < 9; $i++) {
            $total_nilai_unsur[$i] += array_values($row)[$i];
        }
    }

    if ($total_responden > 0) {
        $total_nrr_tertinggi = 0;
        foreach ($total_nilai_unsur as $nilai_unsur) {
            $nrr = ($nilai_unsur / $total_responden);
            $total_nrr_tertinggi += $nrr * 0.11; // 0.11 karena terdapat 9 unsur layanan
        }
        return $total_nrr_tertinggi * 25;
    } else {
        return 0;
    }
}

// Fungsi untuk menghitung perubahan IKM
function hitungPerubahanIKM($unit_layanan = null)
{
    $ikm_tahun_ini = hitungIKM(2023, $unit_layanan);
    $ikm_tahun_sebelumnya = hitungIKM(2022, $unit_layanan);

    if ($ikm_tahun_sebelumnya > 0) {
        return (($ikm_tahun_ini - $ikm_tahun_sebelumnya) / $ikm_tahun_sebelumnya) * 100;
    } else {
        return 0;
    }
}

// Contoh perhitungan untuk BITC dan CTP
$ikm_bitc = hitungIKM(2023, 'Gedung Baros Information Technology Center');
$ikm_ctp = hitungIKM(2023, 'Gedung Cimahi Techno Park');
$perubahan_bitc = hitungPerubahanIKM('Gedung Baros Information Technology Center');
$perubahan_ctp = hitungPerubahanIKM('Gedung Cimahi Techno Park');