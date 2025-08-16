<?php
// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_user = getCurrentUser();

// Function to check if menu item is active
function isActive($page_names) {
    global $current_page;
    if (is_array($page_names)) {
        return in_array($current_page, $page_names) ? 'active' : '';
    }
    return $current_page === $page_names ? 'active' : '';
}
?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="index.php">
      <img src="assets/img/logos/logo.png" class="navbar-brand-img h-500" alt="main_logo">
      <span class="ms-1 font-weight-bold">Zoya Cookies</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  
  <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <!-- Dashboard -->
      <li class="nav-item">
        <a class="nav-link <?php echo isActive(['dashboard', 'index']); ?>" href="dashboard.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <!-- Jabatan -->
      <li class="nav-item">
        <a class="nav-link <?php echo isActive(['jabatan', 'jabatan_tambah', 'jabatan_edit', 'jabatan_hapus']); ?>" href="jabatan.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-badge text-warning text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Data Jabatan</span>
        </a>
      </li>
      <!-- Karyawan -->
      <li class="nav-item">
        <a class="nav-link <?php echo isActive(['karyawan', 'karyawan_tambah', 'karyawan_edit', 'karyawan_hapus']); ?>" href="karyawan.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-02 text-info text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Data Karyawan</span>
        </a>
      </li>
      <!-- Gaji -->
      <li class="nav-item">
        <a class="nav-link <?php echo isActive(['gaji', 'gaji_tambah', 'gaji_edit', 'gaji_hapus']); ?>" href="gaji.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-money-coins text-success text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Data Gaji</span>
        </a>
      </li>
      <!-- Absensi -->
       <li class="nav-item">
        <a class="nav-link <?php echo isActive(['absensi', 'absensi_tambah', 'absensi_edit', 'absensi_hapus']); ?>" href="absensi.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-money-coins text-success text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Data Absensi</span>
        </a>
      </li>
    </ul>
  </div>
  
  <div class="sidenav-footer mx-3">
    <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
      <div class="full-background" style="background-image: url('assets/img/curved-images/white-curved.jpg')"></div>
      <div class="card-body text-start p-3 w-100">
        <div class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
          <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
        </div>
        <div class="docs-info">
          <h6 class="text-white up mb-0"><?php echo htmlspecialchars($current_user['full_name']); ?></h6>
          <p class="text-xs font-weight-bold"><?php echo ucfirst($current_user['role']); ?></p>
          <a href="logout.php" class="btn btn-white btn-sm w-100 mb-0">Logout</a>
        </div>
      </div>
    </div>
  </div>
</aside>
