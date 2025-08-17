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

        </div>
      </div>
    </div>
  </div>
</div>

<?php 
$content = ob_get_clean();
include 'includes/layout.php';