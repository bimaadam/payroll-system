<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Data Jabatan - Sistem Penggajian';

// Start output buffering for content
ob_start();
?>

<!-- Jabatan Content -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6>Data Jabatan</h6>
        <a href="jabatan_tambah.php" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> Tambah Jabatan
        </a>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0" id="jabatanTable">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Jabatan</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Jabatan</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Dibuat</th>
                <th class="text-secondary opacity-7">Aksi</th>
              </tr>
            </thead>
            <tbody id="jabatanTableBody">
              <!-- Data will be loaded here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Load jabatan data
function loadJabatan() {
  $.ajax({
    url: 'api/jabatan.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let tbody = $('#jabatanTableBody');
        tbody.empty();
        
        response.data.forEach(function(jabatan) {
          let row = `
            <tr>
              <td>
                <div class="d-flex px-2 py-1">
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="mb-0 text-sm">${jabatan.kode_jabatan}</h6>
                  </div>
                </div>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">${jabatan.nama_jabatan}</p>
              </td>
              <td>
                <p class="text-xs text-secondary mb-0">${jabatan.created_at}</p>
              </td>
              <td class="align-middle">
                <a href="jabatan_edit.php?id=${jabatan.kode_jabatan}" class="btn btn-sm btn-outline-primary me-2">
                  <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteJabatan('${jabatan.kode_jabatan}')">
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
      Swal.fire('Error', 'Gagal memuat data jabatan', 'error');
    }
  });
}

// Delete jabatan
function deleteJabatan(kode) {
  Swal.fire({
    title: 'Konfirmasi',
    text: 'Apakah Anda yakin ingin menghapus jabatan ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `jabatan_hapus.php?id=${kode}`;
    }
  });
}

// Initialize page
$(document).ready(function() {
  loadJabatan();
});
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
