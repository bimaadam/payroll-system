<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title><?php echo isset($page_title) ? $page_title : 'Sistem Penggajian'; ?></title>
  
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <!-- <link href="assets/css/nucleo-icons.css" rel="stylesheet" /> -->
  <!-- <link href="assets/css/nucleo-svg.css" rel="stylesheet" /> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  
  <!-- Custom CSS for Date Picker -->
  <link href="assets/css/custom-datepicker.css" rel="stylesheet" />
  <?php if (isset($custom_css)) echo $custom_css; ?>
  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- Custom CSS -->
  <style>
    .sidenav .navbar-nav .nav-link.active {
      background-image: linear-gradient(310deg, #7928CA 0%, #FF0080 100%);
      box-shadow: 0 3px 5px -1px rgba(0, 0, 0, 0.09), 0 2px 3px -1px rgba(0, 0, 0, 0.07);
      color: white !important;
      transform: translateX(5px);
    }
    
    .sidenav .navbar-nav .nav-link.active .nav-link-text {
      color: white !important;
      font-weight: 600;
    }
    
    .sidenav .navbar-nav .nav-link.active .icon {
      background: rgba(255, 255, 255, 0.2) !important;
      transform: scale(1.1);
    }
    
    .sidenav .navbar-nav .nav-link.active .icon i {
      color: white !important;
    }
    
    /* Fix sidebar hover effects */
    .sidenav .navbar-nav .nav-link {
      transition: all 0.2s ease-in-out;
      border-radius: 0.5rem;
      margin: 0 0.75rem;
    }
    
    .sidenav .navbar-nav .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
      transform: translateX(5px);
    }
    
    .sidenav .navbar-nav .nav-link:hover .icon {
      transform: scale(1.1);
    }
    
    .sidenav .navbar-nav .nav-link .icon {
      transition: transform 0.2s ease-in-out;
    }
    
    .card {
      box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
    }
    
    .btn-primary {
      background-image: linear-gradient(310deg, #7928CA 0%, #FF0080 100%);
      border: none;
    }
    
    .btn-primary:hover {
      background-image: linear-gradient(310deg, #8e44ad 0%, #e91e63 100%);
      transform: scale(1.02);
      box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
    }
    
    .navbar-main {
      backdrop-filter: saturate(200%) blur(30px);
      background-color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .input-group-outline .form-control {
      background: #fff;
      border: 1px solid #d2d6da;
      border-radius: 0.5rem;
    }
    
    .table th {
      border-bottom: 1px solid #e9ecef;
      color: #67748e;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
    }
    
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    
    .spinner {
      width: 40px;
      height: 40px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #7928CA;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .fade-in {
      animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-200">
  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay" style="display: none;">
    <div class="spinner"></div>
  </div>
  
  <!-- Sidebar -->
  <?php include 'includes/sidebar.php'; ?>
  
  <!-- Main Content -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Page Content -->
    <div class="container-fluid py-4" id="main-content">
      <?php echo $content; ?>
    </div>
    
    <!-- Footer -->
    <footer class="footer py-4">
      <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="copyright text-center text-sm text-muted text-lg-start">
              © <script>document.write(new Date().getFullYear())</script>,
              Zoya Cookies <i class="fa fa-heart text-danger"></i> 
            </div>
          </div>
          <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="#" class="nav-link text-muted">About</a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link text-muted">Support</a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link text-muted">License</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
  </main>
  
  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/plugins/chartjs.min.js"></script>
  <script src="assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>
  
  <!-- Custom JavaScript -->
  <script>
    // Show loading overlay
    function showLoading() {
      $('#loadingOverlay').fadeIn(200);
    }
    
    // Hide loading overlay
    function hideLoading() {
      $('#loadingOverlay').fadeOut(200);
    }
    
    // Load different pages with improved UX
    function loadPage(page) {
      showLoading();
      $('#breadcrumb-current').text(getPageTitle(page));
      
      // Update active menu
      $('.nav-link').removeClass('active bg-gradient-primary');
      $(`.nav-link[onclick="loadPage('${page}')"]`).addClass('active bg-gradient-primary');
      
      $.ajax({
        url: 'pages/' + page + '.php',
        method: 'GET',
        success: function(response) {
          $('#main-content').html(response).addClass('fade-in');
          hideLoading();
          
          // Initialize any new components
          initializeComponents();
        },
        error: function() {
          hideLoading();
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Gagal memuat halaman. Silakan coba lagi.',
            confirmButtonColor: '#7928CA'
          });
        }
      });
    }
    
    // Get page title
    function getPageTitle(page) {
      const titles = {
        'jabatan': 'Data Jabatan',
        'karyawan': 'Data Karyawan',
        'gaji': 'Data Gaji',
        'laporan': 'Laporan Gaji',
        'users': 'Kelola User',
        'settings': 'Pengaturan',
        'profile': 'Profil Saya'
      };
      return titles[page] || 'Dashboard';
    }
    
    // Initialize components
    function initializeComponents() {
      // Initialize tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
      
      // Initialize perfect scrollbar
      if (document.querySelector('.ps')) {
        const ps = new PerfectScrollbar('.ps');
      }
    }
    
    // Number formatting
    function formatCurrency(amount) {
      return new Intl.NumberFormat('id-ID').format(amount);
    }
    
    // Format date
    function formatDate(dateString) {
      let date = new Date(dateString);
      return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    }
    
    // Initialize on document ready
    $(document).ready(function() {
      initializeComponents();
      
      // Auto-hide alerts after 5 seconds
      setTimeout(function() {
        $('.alert').fadeOut();
      }, 5000);
      
      // Search functionality
      $('#searchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        if (searchTerm.length > 2) {
          // Implement search logic here
          console.log('Searching for:', searchTerm);
        }
      });
    });
  </script>
  
  <?php if (isset($additional_js)): ?>
    <?php echo $additional_js; ?>
  <?php endif; ?>
</body>

</html>
