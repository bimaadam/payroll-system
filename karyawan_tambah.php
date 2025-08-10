<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Tambah Karyawan - Sistem Penggajian';

// Add custom CSS for date picker
$custom_css = '<link href="assets/css/custom-datepicker.css" rel="stylesheet" />';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'koneksi.php';
    
    $id_karyawan = $_POST['id_karyawan'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $ttl = $_POST['ttl'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $kode_jabatan = $_POST['kode_jabatan'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO karyawan (id_karyawan, nama_karyawan, ttl, jenis_kelamin, alamat, no_hp, kode_jabatan) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$id_karyawan, $nama_karyawan, $ttl, $jenis_kelamin, $alamat, $no_hp, $kode_jabatan]);
        
        if ($result) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Berhasil', 'Karyawan berhasil ditambahkan', 'success').then(() => {
                        window.location.href = 'karyawan.php';
                    });
                });
            </script>";
        } else {
            $error_message = "Gagal menambahkan karyawan";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error_message = "ID karyawan sudah ada";
        } else {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Start output buffering for content
ob_start();
?>

<!-- Tambah Karyawan Content -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6>Tambah Karyawan</h6>
        <a href="karyawan.php" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
      </div>
      <div class="card-body">
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" id="karyawanForm">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="id_karyawan" class="form-control-label">ID Karyawan</label>
                <input class="form-control" type="text" id="id_karyawan" name="id_karyawan" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama_karyawan" class="form-control-label">Nama Karyawan</label>
                <input class="form-control" type="text" id="nama_karyawan" name="nama_karyawan" required>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="tempat_lahir" class="form-control-label">Tempat Lahir</label>
                <input class="form-control" type="text" id="tempat_lahir" name="tempat_lahir" required placeholder="Jakarta">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group date-input-group">
                <label for="tanggal_lahir" class="form-control-label">Tanggal Lahir</label>
                <input class="form-control" type="date" id="tanggal_lahir" name="tanggal_lahir" required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="form-control-label">Preview TTL</label>
                <div class="ttl-preview" id="ttl_preview">Akan muncul setelah mengisi tempat dan tanggal lahir</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="jenis_kelamin" class="form-control-label">Jenis Kelamin</label>
                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                  <option value="">Pilih Jenis Kelamin</option>
                  <option value="Laki-laki">Laki-laki</option>
                  <option value="Perempuan">Perempuan</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="no_hp" class="form-control-label">No HP</label>
                <input class="form-control" type="text" id="no_hp" name="no_hp" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="kode_jabatan" class="form-control-label">Jabatan</label>
                <select class="form-control" id="kode_jabatan" name="kode_jabatan" required>
                  <option value="">Pilih Jabatan</option>
                  <!-- Options will be loaded via AJAX -->
                </select>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="alamat" class="form-control-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
          </div>
          
          <div class="form-group">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan
            </button>
            <a href="karyawan.php" class="btn btn-secondary">
              <i class="fas fa-times"></i> Batal
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Load jabatan options
function loadJabatan() {
  $.ajax({
    url: 'api/jabatan.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let select = $('#kode_jabatan');
        select.empty().append('<option value="">Pilih Jabatan</option>');
        
        response.data.forEach(function(jabatan) {
          select.append(`<option value="${jabatan.kode_jabatan}">${jabatan.nama_jabatan}</option>`);
        });
      }
    }
  });
}

// Combine tempat lahir and tanggal lahir into TTL format
function updateTTL() {
  let tempatLahir = $('#tempat_lahir').val();
  let tanggalLahir = $('#tanggal_lahir').val();
  
  if (tempatLahir && tanggalLahir) {
    // Convert date format from YYYY-MM-DD to DD Month YYYY (Indonesian)
    let date = new Date(tanggalLahir);
    let months = [
      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    let day = date.getDate().toString().padStart(2, '0');
    let month = months[date.getMonth()];
    let year = date.getFullYear();
    
    let formattedDate = `${day} ${month} ${year}`;
    let ttl = `${tempatLahir}, ${formattedDate}`;
    
    // Update preview
    $('#ttl_preview').text(ttl).addClass('filled');
    
    // Create hidden input for TTL
    $('#ttl_hidden').remove();
    $('<input>').attr({
      type: 'hidden',
      id: 'ttl_hidden',
      name: 'ttl',
      value: ttl
    }).appendTo('#karyawanForm');
  } else {
    // Clear preview if incomplete
    $('#ttl_preview').text('Akan muncul setelah mengisi tempat dan tanggal lahir').removeClass('filled');
  }
}

// Initialize page
$(document).ready(function() {
  loadJabatan();
  
  // Auto-generate ID karyawan
  let timestamp = Date.now().toString().slice(-6);
  $('#id_karyawan').val('K' + timestamp);
  
  // Update TTL when tempat lahir or tanggal lahir changes
  $('#tempat_lahir, #tanggal_lahir').on('input change', updateTTL);
});
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
