<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Dashboard - Zoya Cookies';

// Start output buffering for content
ob_start();
?>

<!-- Dashboard Content -->
<div class="row">
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Jabatan</p>
              <h5 class="font-weight-bolder mb-0" id="total-jabatan">0</h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="ni ni-badge text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Karyawan</p>
              <h5 class="font-weight-bolder mb-0" id="total-karyawan">0</h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Gaji</p>
              <h5 class="font-weight-bolder mb-0" id="total-gaji">0</h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-xl-3 col-sm-6">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Penggajian</p>
              <h5 class="font-weight-bolder mb-0" id="total-penggajian">Rp 0</h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Selamat Datang di Sistem Penggajian</h6>
      </div>
      <div class="card-body">
        <p class="text-sm">
          Sistem ini memungkinkan Anda untuk mengelola data jabatan, karyawan, dan gaji dengan mudah. 
          Gunakan menu di sebelah kiri untuk navigasi ke berbagai fitur yang tersedia.
        </p>
        <div class="row">
          <div class="col-md-4">
            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3">
                <i class="ni ni-badge text-white opacity-10"></i>
              </div>
              <h6 class="mb-0">Data Jabatan</h6>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md me-3">
                <i class="ni ni-single-02 text-white opacity-10"></i>
              </div>
              <h6 class="mb-0">Data Karyawan</h6>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
              <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md me-3">
                <i class="ni ni-money-coins text-white opacity-10"></i>
              </div>
              <h6 class="mb-0">Data Gaji</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Load dashboard statistics
function loadDashboardStats() {
  $.ajax({
    url: 'api/dashboard_stats.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        $('#total-jabatan').text(response.total_jabatan);
        $('#total-karyawan').text(response.total_karyawan);
        $('#total-gaji').text(response.total_gaji);
        $('#total-penggajian').text('Rp ' + formatCurrency(response.total_penggajian));
      }
    },
    error: function() {
      console.error('Failed to load dashboard stats');
    }
  });
}

// Number formatting
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount);
}

// Initialize dashboard
$(document).ready(function() {
  loadDashboardStats();
  
  // Refresh stats every 30 seconds
  setInterval(loadDashboardStats, 30000);
});
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
