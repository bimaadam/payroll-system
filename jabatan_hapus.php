<?php
require_once 'auth.php';
requireLogin();

// Handle delete action
if (isset($_GET['id'])) {
    require_once 'koneksi.php';
    
    $kode_jabatan = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM jabatan WHERE kode_jabatan = ?");
        $result = $stmt->execute([$kode_jabatan]);
        
        if ($result) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Berhasil', 'Jabatan berhasil dihapus', 'success').then(() => {
                        window.location.href = 'jabatan.php';
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Error', 'Gagal menghapus jabatan', 'error').then(() => {
                        window.location.href = 'jabatan.php';
                    });
                });
            </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire('Error', 'Error: " . addslashes($e->getMessage()) . "', 'error').then(() => {
                    window.location.href = 'jabatan.php';
                });
            });
        </script>";
    }
} else {
    header('Location: jabatan.php');
    exit();
}

$page_title = 'Hapus Jabatan - Sistem Penggajian';

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
        <p class="mt-3">Menghapus jabatan...</p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
