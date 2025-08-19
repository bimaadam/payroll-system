<?php
require_once '../koneksi.php';

header('Content-Type: application/json');

$result = $conn->query("SELECT a.kode_absensi, k.nama_karyawan, j.nama_jabatan, a.tanggal, a.jam_masuk, a.jam_pulang, a.status FROM absensi a JOIN karyawan k ON a.id_karyawan = k.id_karyawan JOIN jabatan j ON k.kode_jabatan = j.kode_jabatan ORDER BY a.tanggal DESC");

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['data' => $data]);
?>
