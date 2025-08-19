<?php session_start();
require_once 'auth.php';
requireLogin();
$current_user = getCurrentUser();
$page_title = "Absensi - Zoya Cookies";

ob_start();
?>

<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6>Data Absensi</h6>
        <a href="absensi_tambah.php" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> Tambah Data Absensi
        </a>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0" id="absensiTable">
  <thead>
    <tr>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Absensi</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Karyawan</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jabatan</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jam Masuk</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jam Pulang</th>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
      <th class="text-secondary opacity-7">Aksi</th>
    </tr>
  </thead>
  <tbody id="absensiTableBody">
    <!-- Data absensi akan dimuat di sini -->
  </tbody>
</table>

<script>
$(document).ready(function() {
    $.ajax({
        url: 'api/absensi.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.data) {
                let tableBody = '';
                response.data.forEach(function(item) {
                    tableBody += '<tr>';
                    tableBody += '<td><div class="d-flex px-2 py-1"><div class="d-flex flex-column justify-content-center"><h6 class="mb-0 text-sm">' + item.kode_absensi + '</h6></div></div></td>';
                    tableBody += '<td><p class="text-xs font-weight-bold mb-0">' + item.nama_karyawan + '</p></td>';
                    tableBody += '<td><p class="text-xs font-weight-bold mb-0">' + item.nama_jabatan + '</p></td>';
                    tableBody += '<td><p class="text-xs font-weight-bold mb-0">' + item.tanggal + '</p></td>';
                    tableBody += '<td><p class="text-xs font-weight-bold mb-0">' + item.jam_masuk + '</p></td>';
                    tableBody += '<td><p class="text-xs font-weight-bold mb-0">' + item.jam_pulang + '</p></td>';
                    tableBody += '<td><p class="text-xs font-weight-bold mb-0">' + item.status + '</p></td>';
                    tableBody += '<td class="align-middle">';
                    tableBody += '<a href="absensi_edit.php?id=' + item.kode_absensi + '" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-edit"></i></a>';
                    tableBody += '<button class="btn btn-sm btn-outline-danger" onclick="deleteAbsensi(\'" + item.kode_absensi + "\'\)"><i class="fas fa-trash"></i></button>';
                    tableBody += '</td>';
                    tableBody += '</tr>';
                });
                $('#absensiTableBody').html(tableBody);
            }
        },
        error: function() {
            console.error('Failed to load attendance data');
        }
    });
});

function deleteAbsensi(kode) {
  Swal.fire({
    title: 'Konfirmasi',
    text: 'Apakah Anda yakin ingin menghapus data absensi ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `absensi_hapus.php?id=${kode}`;
    }
  });
}
</script>

        </div>
      </div>
    </div>
  </div>
</div>

<?php 
$content = ob_get_clean();
include 'includes/layout.php';