<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Data Karyawan - Sistem Penggajian';

// Start output buffering for content
ob_start();
?>

<!-- Karyawan Content -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6>Data Karyawan</h6>
        <a href="karyawan_tambah.php" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> Tambah Karyawan
        </a>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0" id="karyawanTable">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">TTL</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis Kelamin</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jabatan</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">No HP</th>
                <th class="text-secondary opacity-7">Aksi</th>
              </tr>
            </thead>
            <tbody id="karyawanTableBody">
              <!-- Data will be loaded here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Load karyawan data
function loadKaryawan() {
  $.ajax({
    url: 'api/karyawan.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let tbody = $('#karyawanTableBody');
        tbody.empty();
        
        response.data.forEach(function(karyawan) {
          let row = `
            <tr>
              <td>
                <div class="d-flex px-2 py-1">
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="mb-0 text-sm">${karyawan.id_karyawan}</h6>
                  </div>
                </div>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">${karyawan.nama_karyawan}</p>
              </td>
              <td>
                <p class="text-xs text-secondary mb-0">${karyawan.ttl}</p>
              </td>
              <td>
                <p class="text-xs text-secondary mb-0">${karyawan.jenis_kelamin}</p>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">${karyawan.nama_jabatan}</p>
              </td>
              <td>
                <p class="text-xs text-secondary mb-0">${karyawan.no_hp}</p>
              </td>
              <td class="align-middle">
                <a href="karyawan_edit.php?id=${karyawan.id_karyawan}" class="btn btn-sm btn-outline-primary me-2">
                  <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteKaryawan('${karyawan.id_karyawan}')">
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
      Swal.fire('Error', 'Gagal memuat data karyawan', 'error');
    }
  });
}

// Delete karyawan
function deleteKaryawan(id) {
  Swal.fire({
    title: 'Konfirmasi',
    text: 'Apakah Anda yakin ingin menghapus karyawan ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `karyawan_hapus.php?id=${id}`;
    }
  });
}

// Initialize page
$(document).ready(function() {
  loadKaryawan();
});
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
