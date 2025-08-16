<?php session_start();
require_once 'auth.php';
requireLogin();
$current_user = getCurrentUser();
$page_title = "Absensi - Zoya Cookies";

ob_start();
?>



<?php 
$content = ob_get_clean();
include 'includes/layout.php';