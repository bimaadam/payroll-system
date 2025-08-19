<?php
require_once 'auth.php';
requireLogin();
require_once 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: absensi.php");
    exit();
}

$kode_absensi = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM absensi WHERE kode_absensi = ?");
$stmt->bind_param("s", $kode_absensi);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Data absensi berhasil dihapus.";
} else {
    $_SESSION['error_message'] = "Error: " . $stmt->error;
}

header("Location: absensi.php");
exit();
?>
