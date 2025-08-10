<?php
require_once '../koneksi.php';
require_once '../auth.php';

// Require login to access API
requireLogin();

header('Content-Type: application/json');

try {
    // Get total jabatan
    $result_jabatan = $conn->query("SELECT COUNT(*) as total FROM jabatan");
    $total_jabatan = $result_jabatan->fetch_assoc()['total'];
    
    // Get total karyawan
    $result_karyawan = $conn->query("SELECT COUNT(*) as total FROM karyawan");
    $total_karyawan = $result_karyawan->fetch_assoc()['total'];
    
    // Get total gaji records
    $result_gaji = $conn->query("SELECT COUNT(*) as total FROM gaji");
    $total_gaji = $result_gaji->fetch_assoc()['total'];
    
    // Get total penggajian amount (calculate from gaji_pokok + tunjangan + bonus)
    $result_penggajian = $conn->query("SELECT SUM(gaji_pokok + tunjangan + bonus) as total FROM gaji");
    $total_penggajian = $result_penggajian->fetch_assoc()['total'] ?? 0;
    
    $response = [
        'success' => true,
        'total_jabatan' => $total_jabatan,
        'total_karyawan' => $total_karyawan,
        'total_gaji' => $total_gaji,
        'total_penggajian' => $total_penggajian
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
