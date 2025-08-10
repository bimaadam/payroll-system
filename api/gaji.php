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
            if (isset($_GET['kode_gaji'])) {
                // Get single gaji
                $kode = $_GET['kode_gaji'];
                $stmt = $conn->prepare("SELECT g.*, k.nama_karyawan, j.nama_jabatan FROM gaji g LEFT JOIN karyawan k ON g.id_karyawan = k.id_karyawan LEFT JOIN jabatan j ON k.kode_jabatan = j.kode_jabatan WHERE g.kode_gaji = ?");
                $stmt->bind_param("s", $kode);
                $stmt->execute();
                $result = $stmt->get_result();
                $gaji = $result->fetch_assoc();
                
                if ($gaji) {
                    echo json_encode(['success' => true, 'data' => $gaji]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Data gaji tidak ditemukan']);
                }
            } else {
                // Get all gaji
                $result = $conn->query("SELECT g.*, k.nama_karyawan, j.nama_jabatan FROM gaji g LEFT JOIN karyawan k ON g.id_karyawan = k.id_karyawan LEFT JOIN jabatan j ON k.kode_jabatan = j.kode_jabatan ORDER BY g.kode_gaji");
                $gaji = [];
                while ($row = $result->fetch_assoc()) {
                    $gaji[] = $row;
                }
                echo json_encode(['success' => true, 'data' => $gaji]);
            }
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $kode_gaji = $input['kode_gaji'];
            $id_karyawan = $input['id_karyawan'];
            $tunjangan = $input['tunjangan'];
            $bonus = $input['bonus'];
            $gaji_pokok = $input['gaji_pokok'];
            
            // Check if karyawan already has gaji record
            $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM gaji WHERE id_karyawan = ?");
            $check_stmt->bind_param("i", $id_karyawan);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $count = $check_result->fetch_assoc()['count'];
            
            if ($count > 0) {
                echo json_encode(['success' => false, 'message' => 'Karyawan sudah memiliki data gaji']);
            } else {
                $stmt = $conn->prepare("INSERT INTO gaji (kode_gaji, id_karyawan, tunjangan, bonus, gaji_pokok) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("siddd", $kode_gaji, $id_karyawan, $tunjangan, $bonus, $gaji_pokok);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Data gaji berhasil ditambahkan']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
                }
            }
            break;
            
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            $kode_gaji = $input['kode_gaji'];
            $id_karyawan = $input['id_karyawan'];
            $tunjangan = $input['tunjangan'];
            $bonus = $input['bonus'];
            $gaji_pokok = $input['gaji_pokok'];
            
            $stmt = $conn->prepare("UPDATE gaji SET id_karyawan = ?, tunjangan = ?, bonus = ?, gaji_pokok = ? WHERE kode_gaji = ?");
            $stmt->bind_param("iddds", $id_karyawan, $tunjangan, $bonus, $gaji_pokok, $kode_gaji);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Data gaji berhasil diupdate']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
            }
            break;
            
        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $kode_gaji = $input['kode_gaji'];
            
            $stmt = $conn->prepare("DELETE FROM gaji WHERE kode_gaji = ?");
            $stmt->bind_param("s", $kode_gaji);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Data gaji berhasil dihapus']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
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
