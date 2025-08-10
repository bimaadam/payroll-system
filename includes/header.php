<?php
$current_user = getCurrentUser();
?>

<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Sistem</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page" id="breadcrumb-current">Dashboard</li>
      </ol>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <div class="input-group">
          <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
          <input type="text" class="form-control" placeholder="Cari data..." id="searchInput">
        </div>
      </div>
      <ul class="navbar-nav justify-content-end">
        <!-- Notifications -->
        <li class="nav-item dropdown pe-2 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="material-icons cursor-pointer">notifications</i>
            <span class="badge badge-sm bg-gradient-success position-absolute top-0 end-0 translate-middle rounded-pill">3</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="javascript:;">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <i class="material-icons text-success me-3">check_circle</i>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      Data gaji berhasil diperbarui
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                      <i class="fa fa-clock me-1"></i>
                      2 menit yang lalu
                    </p>
                  </div>
                </div>
              </a>
            </li>
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="javascript:;">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <i class="material-icons text-info me-3">person_add</i>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      Karyawan baru ditambahkan
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                      <i class="fa fa-clock me-1"></i>
                      5 menit yang lalu
                    </p>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <a class="dropdown-item border-radius-md" href="javascript:;">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <i class="material-icons text-warning me-3">warning</i>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      Backup database diperlukan
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                      <i class="fa fa-clock me-1"></i>
                      1 jam yang lalu
                    </p>
                  </div>
                </div>
              </a>
            </li>
          </ul>
        </li>
        
        <!-- User Profile -->
        <li class="nav-item dropdown pe-2 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuUser" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="d-flex align-items-center">
              <img src="assets/img/team-2.jpg" class="avatar avatar-sm me-2" alt="user-avatar">
              <span class="d-sm-inline d-none font-weight-bold"><?php echo htmlspecialchars($current_user['full_name']); ?></span>
              <i class="material-icons opacity-5 ms-1">keyboard_arrow_down</i>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuUser">
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="javascript:;">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <img src="assets/img/team-2.jpg" class="avatar avatar-sm me-3">
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      <span class="font-weight-bold"><?php echo htmlspecialchars($current_user['full_name']); ?></span>
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                      <i class="material-icons text-xs me-1">badge</i>
                      <?php echo ucfirst($current_user['role']); ?>
                    </p>
                  </div>
                </div>
              </a>
            </li>
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="#" onclick="loadPage('profile')">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <i class="material-icons text-secondary me-3">person</i>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      Profil Saya
                    </h6>
                  </div>
                </div>
              </a>
            </li>
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="#" onclick="loadPage('settings')">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <i class="material-icons text-secondary me-3">settings</i>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      Pengaturan
                    </h6>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <a class="dropdown-item border-radius-md" href="logout.php">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <i class="material-icons text-danger me-3">logout</i>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1 text-danger">
                      Logout
                    </h6>
                  </div>
                </div>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->
