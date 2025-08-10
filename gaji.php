<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Data Gaji - Sistem Penggajian';

// Start output buffering for content
ob_start();
?>

<!-- Gaji Content -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6>Data Gaji</h6>
        <a href="gaji_tambah.php" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> Tambah Data Gaji
        </a>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0" id="gajiTable">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Gaji</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Karyawan</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jabatan</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Gaji Pokok</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tunjangan</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bonus</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Gaji</th>
                <th class="text-secondary opacity-7">Aksi</th>
              </tr>
            </thead>
            <tbody id="gajiTableBody">
              <!-- Data will be loaded here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Load gaji data
function loadGaji() {
  $.ajax({
    url: 'api/gaji.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let tbody = $('#gajiTableBody');
        tbody.empty();
        
        response.data.forEach(function(gaji) {
          let totalGaji = parseInt(gaji.gaji_pokok) + parseInt(gaji.tunjangan) + parseInt(gaji.bonus);
          let row = `
            <tr>
              <td>
                <div class="d-flex px-2 py-1">
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="mb-0 text-sm">${gaji.kode_gaji}</h6>
                  </div>
                </div>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">${gaji.nama_karyawan}</p>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">${gaji.nama_jabatan}</p>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">Rp ${formatCurrency(gaji.gaji_pokok)}</p>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">Rp ${formatCurrency(gaji.tunjangan)}</p>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">Rp ${formatCurrency(gaji.bonus)}</p>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0 text-success">Rp ${formatCurrency(totalGaji)}</p>
              </td>
              <td class="align-middle">
                <a href="gaji_edit.php?id=${gaji.kode_gaji}" class="btn btn-sm btn-outline-primary me-2">
                  <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteGaji('${gaji.kode_gaji}')">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          `;
          tbody.append(row);
        });
      } else {
        Swal.fire('Error', response.message, 'error');
      }
    },
    error: function() {
      Swal.fire('Error', 'Gagal memuat data gaji', 'error');
    }
  });
}

// Delete gaji
function deleteGaji(kode) {
  Swal.fire({
    title: 'Konfirmasi',
    text: 'Apakah Anda yakin ingin menghapus data gaji ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `gaji_hapus.php?id=${kode}`;
    }
  });
}

// Number formatting
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount);
}

// Initialize page
$(document).ready(function() {
  loadGaji();
});
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
