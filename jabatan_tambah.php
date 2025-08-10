<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Tambah Jabatan - Sistem Penggajian';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'koneksi.php';
    
    $kode_jabatan = $_POST['kode_jabatan'];
    $nama_jabatan = $_POST['nama_jabatan'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO jabatan (kode_jabatan, nama_jabatan) VALUES (?, ?)");
        $result = $stmt->execute([$kode_jabatan, $nama_jabatan]);
        
        if ($result) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Berhasil', 'Jabatan berhasil ditambahkan', 'success').then(() => {
                        window.location.href = 'jabatan.php';
                    });
                });
            </script>";
        } else {
            $error_message = "Gagal menambahkan jabatan";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error_message = "Kode jabatan sudah ada";
        } else {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Start output buffering for content
ob_start();
?>

<!-- Tambah Jabatan Content -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6>Tambah Jabatan</h6>
        <a href="jabatan.php" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
      </div>
      <div class="card-body">
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" id="jabatanForm">
          <div class="form-group">
            <label for="kode_jabatan" class="form-control-label">Kode Jabatan</label>
            <input class="form-control" type="text" id="kode_jabatan" name="kode_jabatan" required>
          </div>
          <div class="form-group">
            <label for="nama_jabatan" class="form-control-label">Nama Jabatan</label>
            <input class="form-control" type="text" id="nama_jabatan" name="nama_jabatan" required>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan
            </button>
            <a href="jabatan.php" class="btn btn-secondary">
              <i class="fas fa-times"></i> Batal
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Initialize page
$(document).ready(function() {
  // Auto-generate kode jabatan
  let timestamp = Date.now().toString().slice(-6);
  $('#kode_jabatan').val('J' + timestamp);
});
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
