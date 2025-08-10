<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Edit Karyawan - Sistem Penggajian';

// Add custom CSS for date picker
$custom_css = '<link href="assets/css/custom-datepicker.css" rel="stylesheet" />';

// Get karyawan data
$karyawan_data = null;
if (isset($_GET['id'])) {
    require_once 'koneksi.php';
    
    try {
        $stmt = $pdo->prepare("
            SELECT k.*, j.nama_jabatan 
            FROM karyawan k 
            JOIN jabatan j ON k.kode_jabatan = j.kode_jabatan 
            WHERE k.id_karyawan = ?
        ");
        $stmt->execute([$_GET['id']]);
        $karyawan_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$karyawan_data) {
            header('Location: karyawan.php');
            exit();
        }
    } catch (PDOException $e) {
        header('Location: karyawan.php');
        exit();
    }
} else {
    header('Location: karyawan.php');
    exit();
}

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
        $stmt = $pdo->prepare("UPDATE karyawan SET nama_karyawan = ?, ttl = ?, jenis_kelamin = ?, alamat = ?, no_hp = ?, kode_jabatan = ? WHERE id_karyawan = ?");
        $result = $stmt->execute([$nama_karyawan, $ttl, $jenis_kelamin, $alamat, $no_hp, $kode_jabatan, $id_karyawan]);
        
        if ($result) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Berhasil', 'Karyawan berhasil diupdate', 'success').then(() => {
                        window.location.href = 'karyawan.php';
                    });
                });
            </script>";
        } else {
            $error_message = "Gagal mengupdate karyawan";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Start output buffering for content
ob_start();
?>

<!-- Edit Karyawan Content -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6>Edit Karyawan</h6>
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
                <input class="form-control" type="text" id="id_karyawan" name="id_karyawan" value="<?php echo htmlspecialchars($karyawan_data['id_karyawan']); ?>" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama_karyawan" class="form-control-label">Nama Karyawan</label>
                <input class="form-control" type="text" id="nama_karyawan" name="nama_karyawan" value="<?php echo htmlspecialchars($karyawan_data['nama_karyawan']); ?>" required>
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
                  <option value="Laki-laki" <?php echo $karyawan_data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                  <option value="Perempuan" <?php echo $karyawan_data['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="no_hp" class="form-control-label">No HP</label>
                <input class="form-control" type="text" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($karyawan_data['no_hp']); ?>" required>
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
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($karyawan_data['alamat']); ?></textarea>
          </div>
          
          <div class="form-group">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Update
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
          let selected = jabatan.kode_jabatan == '<?php echo $karyawan_data['kode_jabatan']; ?>' ? 'selected' : '';
          select.append(`<option value="${jabatan.kode_jabatan}" ${selected}>${jabatan.nama_jabatan}</option>`);
        });
      }
    }
  });
}

// Parse existing TTL data into separate fields
function parseTTL() {
  let ttlValue = "<?php echo htmlspecialchars($karyawan_data['ttl']); ?>";
  if (ttlValue) {
    // Split TTL by comma
    let parts = ttlValue.split(', ');
    if (parts.length >= 2) {
      let tempatLahir = parts[0].trim();
      let tanggalLahirStr = parts[1].trim();
      
      // Set tempat lahir
      $('#tempat_lahir').val(tempatLahir);
      
      // Parse Indonesian date format to YYYY-MM-DD
      let months = {
        'Januari': '01', 'Februari': '02', 'Maret': '03', 'April': '04',
        'Mei': '05', 'Juni': '06', 'Juli': '07', 'Agustus': '08',
        'September': '09', 'Oktober': '10', 'November': '11', 'Desember': '12'
      };
      
      // Parse date like "15 Januari 1990"
      let dateParts = tanggalLahirStr.split(' ');
      if (dateParts.length === 3) {
        let day = dateParts[0].padStart(2, '0');
        let monthName = dateParts[1];
        let year = dateParts[2];
        let monthNum = months[monthName] || '01';
        
        let formattedDate = `${year}-${monthNum}-${day}`;
        $('#tanggal_lahir').val(formattedDate);
      }
    }
  }
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
  
  // Parse existing TTL data
  parseTTL();
  
  // Update TTL when tempat lahir or tanggal lahir changes
  $('#tempat_lahir, #tanggal_lahir').on('input change', updateTTL);
  
  // Initial TTL update
  updateTTL();
});
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
