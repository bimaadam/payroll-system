<?php
// Simple index.php that redirects to dashboard
require_once 'auth.php';
requireLogin();

// Redirect to dashboard
header('Location: dashboard.php');
exit();

