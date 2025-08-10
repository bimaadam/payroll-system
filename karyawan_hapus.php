<?php
require_once 'auth.php';
requireLogin();

// Handle delete action
if (isset($_GET['id'])) {
    require_once 'koneksi.php';
    
    $id_karyawan = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM karyawan WHERE id_karyawan = ?");
        $result = $stmt->execute([$id_karyawan]);
        
        if ($result) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Berhasil', 'Karyawan berhasil dihapus', 'success').then(() => {
                        window.location.href = 'karyawan.php';
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Error', 'Gagal menghapus karyawan', 'error').then(() => {
                        window.location.href = 'karyawan.php';
                    });
                });
            </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire('Error', 'Error: " . addslashes($e->getMessage()) . "', 'error').then(() => {
                    window.location.href = 'karyawan.php';
                });
            });
        </script>";
    }
} else {
    header('Location: karyawan.php');
    exit();
}

$page_title = 'Hapus Karyawan - Sistem Penggajian';

// Start output buffering for content
ob_start();
?>

<!-- Loading Content -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-body text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Menghapus karyawan...</p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
