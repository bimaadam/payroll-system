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
            if (isset($_GET['kode_jabatan'])) {
                // Get single jabatan
                $kode = $_GET['kode_jabatan'];
                $stmt = $conn->prepare("SELECT * FROM jabatan WHERE kode_jabatan = ?");
                $stmt->bind_param("s", $kode);
                $stmt->execute();
                $result = $stmt->get_result();
                $jabatan = $result->fetch_assoc();
                
                if ($jabatan) {
                    echo json_encode(['success' => true, 'data' => $jabatan]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Jabatan tidak ditemukan']);
                }
            } else {
                // Get all jabatan
                $result = $conn->query("SELECT * FROM jabatan ORDER BY kode_jabatan");
                $jabatan = [];
                while ($row = $result->fetch_assoc()) {
                    $jabatan[] = $row;
                }
                echo json_encode(['success' => true, 'data' => $jabatan]);
            }
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $kode_jabatan = $input['kode_jabatan'];
            $nama_jabatan = $input['nama_jabatan'];
            
            $stmt = $conn->prepare("INSERT INTO jabatan (kode_jabatan, nama_jabatan) VALUES (?, ?)");
            $stmt->bind_param("ss", $kode_jabatan, $nama_jabatan);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Jabatan berhasil ditambahkan']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
            }
            break;
            
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            $kode_jabatan = $input['kode_jabatan'];
            $nama_jabatan = $input['nama_jabatan'];
            
            $stmt = $conn->prepare("UPDATE jabatan SET nama_jabatan = ? WHERE kode_jabatan = ?");
            $stmt->bind_param("ss", $nama_jabatan, $kode_jabatan);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Jabatan berhasil diupdate']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
            }
            break;
            
        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $kode_jabatan = $input['kode_jabatan'];
            
            // Check if jabatan is used by karyawan
            $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM karyawan WHERE kode_jabatan = ?");
            $check_stmt->bind_param("s", $kode_jabatan);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $count = $check_result->fetch_assoc()['count'];
            
            if ($count > 0) {
                echo json_encode(['success' => false, 'message' => 'Jabatan tidak dapat dihapus karena masih digunakan oleh karyawan']);
            } else {
                $stmt = $conn->prepare("DELETE FROM jabatan WHERE kode_jabatan = ?");
                $stmt->bind_param("s", $kode_jabatan);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Jabatan berhasil dihapus']);
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
