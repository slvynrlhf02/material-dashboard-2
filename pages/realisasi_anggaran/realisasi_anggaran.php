<?php

require_once('koneksi.php');

// Query untuk mendapatkan data per bulan
$query = "
    SELECT 
        DATE_FORMAT(tanggal, '%Y-%m') AS bulan, 
        SUM(pagu_anggaran) AS total_pagu_anggaran, 
        SUM(realisasi_anggaran) AS total_realisasi_anggaran,
        (SUM(realisasi_anggaran) / SUM(pagu_anggaran)) * 100 AS persentase_realisasi
    FROM 
        realisasi_anggaran
    GROUP BY 
        bulan
    ORDER BY 
        bulan;
";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

function formatCurrency($number) {
    return number_format($number, 0, ',', '.');
}

function formatPercentage($number) {
    return number_format($number, 2, ',', '.') . '%';
}

// Menyimpan data dalam array untuk penggunaan di dashboard atau lainnya
$chartData = [
    'bulan' => [],
    'pagu_anggaran' => [],
    'realisasi_anggaran' => [],
    'persentase_realisasi' => []
];

foreach ($data as $entry) {
    $chartData['bulan'][] = $entry['bulan'];
    $chartData['pagu_anggaran'][] = $entry['total_pagu_anggaran'];
    $chartData['realisasi_anggaran'][] = $entry['total_realisasi_anggaran'];
    $chartData['persentase_realisasi'][] = $entry['persentase_realisasi'];
}

// Anda dapat mengekspor data ini sebagai JSON untuk digunakan di JavaScript
echo json_encode($chartData);

// Atau tampilkan data dalam bentuk tabel untuk debugging atau lainnya
echo "<table border='1'>";
echo "<tr><th>Bulan</th><th>Total Pagu Anggaran</th><th>Total Realisasi Anggaran</th><th>Persentase Realisasi</th></tr>";
foreach ($data as $row) {
    echo "<tr>";
    echo "<td>" . $row['bulan'] . "</td>";
    echo "<td>" . formatCurrency($row['total_pagu_anggaran']) . "</td>";
    echo "<td>" . formatCurrency($row['total_realisasi_anggaran']) . "</td>";
    echo "<td>" . formatPercentage($row['persentase_realisasi']) . "</td>";
    echo "</tr>";
}
echo "</table>";