<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Tambah Data Gaji - Sistem Penggajian';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'koneksi.php';
    
    $kode_gaji = $_POST['kode_gaji'];
    $id_karyawan = $_POST['id_karyawan'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $tunjangan = $_POST['tunjangan'];
    $bonus = $_POST['bonus'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO gaji (kode_gaji, id_karyawan, gaji_pokok, tunjangan, bonus) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$kode_gaji, $id_karyawan, $gaji_pokok, $tunjangan, $bonus]);
        
        if ($result) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Berhasil', 'Data gaji berhasil ditambahkan', 'success').then(() => {
                        window.location.href = 'gaji.php';
                    });
                });
            </script>";
        } else {
            $error_message = "Gagal menambahkan data gaji";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error_message = "Kode gaji sudah ada";
        } else {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Start output buffering for content
ob_start();
?>

<!-- Tambah Gaji Content -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6>Tambah Data Gaji</h6>
        <a href="gaji.php" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
      </div>
      <div class="card-body">
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" id="gajiForm">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="kode_gaji" class="form-control-label">Kode Gaji</label>
                <input class="form-control" type="text" id="kode_gaji" name="kode_gaji" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="id_karyawan" class="form-control-label">Karyawan</label>
                <select class="form-control" id="id_karyawan" name="id_karyawan" required>
                  <option value="">Pilih Karyawan</option>
                  <!-- Options will be loaded via AJAX -->
                </select>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="gaji_pokok" class="form-control-label">Gaji Pokok</label>
                <input class="form-control" type="number" id="gaji_pokok" name="gaji_pokok" required min="0">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="tunjangan" class="form-control-label">Tunjangan</label>
                <input class="form-control" type="number" id="tunjangan" name="tunjangan" required min="0">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="bonus" class="form-control-label">Bonus</label>
                <input class="form-control" type="number" id="bonus" name="bonus" required min="0">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="form-control-label">Total Gaji</label>
                <div class="form-control bg-light" id="total_gaji">Rp 0</div>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan
            </button>
            <a href="gaji.php" class="btn btn-secondary">
              <i class="fas fa-times"></i> Batal
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Load karyawan options
function loadKaryawan() {
  $.ajax({
    url: 'api/karyawan.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let select = $('#id_karyawan');
        select.empty().append('<option value="">Pilih Karyawan</option>');
        
        response.data.forEach(function(karyawan) {
          select.append(`<option value="${karyawan.id_karyawan}">${karyawan.nama_karyawan} - ${karyawan.nama_jabatan}</option>`);
        });
      }
    }
  });
}

// Calculate total gaji
function calculateTotal() {
  let gajiPokok = parseInt($('#gaji_pokok').val()) || 0;
  let tunjangan = parseInt($('#tunjangan').val()) || 0;
  let bonus = parseInt($('#bonus').val()) || 0;
  let total = gajiPokok + tunjangan + bonus;
  
  $('#total_gaji').text('Rp ' + formatCurrency(total));
}

// Number formatting
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount);
}

// Initialize page
$(document).ready(function() {
  loadKaryawan();
  
  // Auto-generate kode gaji
  let timestamp = Date.now().toString().slice(-6);
  $('#kode_gaji').val('G' + timestamp);
  
  // Calculate total when inputs change
  $('#gaji_pokok, #tunjangan, #bonus').on('input', calculateTotal);
});
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
