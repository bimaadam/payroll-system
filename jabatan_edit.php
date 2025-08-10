<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Edit Jabatan - Sistem Penggajian';

// Get jabatan data
$jabatan_data = null;
if (isset($_GET['id'])) {
    require_once 'koneksi.php';
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM jabatan WHERE kode_jabatan = ?");
        $stmt->execute([$_GET['id']]);
        $jabatan_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$jabatan_data) {
            header('Location: jabatan.php');
            exit();
        }
    } catch (PDOException $e) {
        header('Location: jabatan.php');
        exit();
    }
} else {
    header('Location: jabatan.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'koneksi.php';
    
    $kode_jabatan = $_POST['kode_jabatan'];
    $nama_jabatan = $_POST['nama_jabatan'];
    
    try {
        $stmt = $pdo->prepare("UPDATE jabatan SET nama_jabatan = ? WHERE kode_jabatan = ?");
        $result = $stmt->execute([$nama_jabatan, $kode_jabatan]);
        
        if ($result) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Berhasil', 'Jabatan berhasil diupdate', 'success').then(() => {
                        window.location.href = 'jabatan.php';
                    });
                });
            </script>";
        } else {
            $error_message = "Gagal mengupdate jabatan";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Start output buffering for content
ob_start();
?>

<!-- Edit Jabatan Content -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6>Edit Jabatan</h6>
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
            <input class="form-control" type="text" id="kode_jabatan" name="kode_jabatan" value="<?php echo htmlspecialchars($jabatan_data['kode_jabatan']); ?>" readonly>
          </div>
          <div class="form-group">
            <label for="nama_jabatan" class="form-control-label">Nama Jabatan</label>
            <input class="form-control" type="text" id="nama_jabatan" name="nama_jabatan" value="<?php echo htmlspecialchars($jabatan_data['nama_jabatan']); ?>" required>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Update
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

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
