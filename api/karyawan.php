<?php
require_once '../koneksi.php';
require_once '../auth.php';

// Require login to access API
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id_karyawan'])) {
                // Get single karyawan
                $id = $_GET['id_karyawan'];
                $stmt = $conn->prepare("SELECT k.*, j.nama_jabatan FROM karyawan k LEFT JOIN jabatan j ON k.kode_jabatan = j.kode_jabatan WHERE k.id_karyawan = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $karyawan = $result->fetch_assoc();
                
                if ($karyawan) {
                    echo json_encode(['success' => true, 'data' => $karyawan]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Karyawan tidak ditemukan']);
                }
            } else {
                // Get all karyawan
                $result = $conn->query("SELECT k.*, j.nama_jabatan FROM karyawan k LEFT JOIN jabatan j ON k.kode_jabatan = j.kode_jabatan ORDER BY k.id_karyawan");
                $karyawan = [];
                while ($row = $result->fetch_assoc()) {
                    $karyawan[] = $row;
                }
                echo json_encode(['success' => true, 'data' => $karyawan]);
            }
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $nama_karyawan = $input['nama_karyawan'];
            $ttl = $input['ttl'];
            $jenis_kelamin = $input['jenis_kelamin'];
            $alamat = $input['alamat'];
            $no_hp = $input['no_hp'];
            $kode_jabatan = $input['kode_jabatan'];
            
            $stmt = $conn->prepare("INSERT INTO karyawan (nama_karyawan, ttl, jenis_kelamin, alamat, no_hp, kode_jabatan) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nama_karyawan, $ttl, $jenis_kelamin, $alamat, $no_hp, $kode_jabatan);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Karyawan berhasil ditambahkan']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
            }
            break;
            
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            $id_karyawan = $input['id_karyawan'];
            $nama_karyawan = $input['nama_karyawan'];
            $ttl = $input['ttl'];
            $jenis_kelamin = $input['jenis_kelamin'];
            $alamat = $input['alamat'];
            $no_hp = $input['no_hp'];
            $kode_jabatan = $input['kode_jabatan'];
            
            $stmt = $conn->prepare("UPDATE karyawan SET nama_karyawan = ?, ttl = ?, jenis_kelamin = ?, alamat = ?, no_hp = ?, kode_jabatan = ? WHERE id_karyawan = ?");
            $stmt->bind_param("ssssssi", $nama_karyawan, $ttl, $jenis_kelamin, $alamat, $no_hp, $kode_jabatan, $id_karyawan);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Karyawan berhasil diupdate']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
            }
            break;
            
        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $id_karyawan = $input['id_karyawan'];
            
            // Check if karyawan has gaji records
            $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM gaji WHERE id_karyawan = ?");
            $check_stmt->bind_param("i", $id_karyawan);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $count = $check_result->fetch_assoc()['count'];
            
            if ($count > 0) {
                echo json_encode(['success' => false, 'message' => 'Karyawan tidak dapat dihapus karena memiliki data gaji']);
            } else {
                $stmt = $conn->prepare("DELETE FROM karyawan WHERE id_karyawan = ?");
                $stmt->bind_param("i", $id_karyawan);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Karyawan berhasil dihapus']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
                }
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>
