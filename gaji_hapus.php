<?php
require_once 'auth.php';
requireLogin();

// Handle delete action
if (isset($_GET['id'])) {
    require_once 'koneksi.php';
    
    $kode_gaji = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM gaji WHERE kode_gaji = ?");
        $result = $stmt->execute([$kode_gaji]);
        
        if ($result) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Berhasil', 'Data gaji berhasil dihapus', 'success').then(() => {
                        window.location.href = 'gaji.php';
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Error', 'Gagal menghapus data gaji', 'error').then(() => {
                        window.location.href = 'gaji.php';
                    });
                });
            </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire('Error', 'Error: " . addslashes($e->getMessage()) . "', 'error').then(() => {
                    window.location.href = 'gaji.php';
                });
            });
        </script>";
    }
} else {
    header('Location: gaji.php');
    exit();
}

$page_title = 'Hapus Data Gaji - Sistem Penggajian';

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
        <p class="mt-3">Menghapus data gaji...</p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
