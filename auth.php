<?php
session_start();
require_once 'koneksi.php';

// Function to create users table
function createUsersTable() {
    global $conn;
    
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        // Create default admin user if not exists
        $default_username = 'admin';
        $default_password = password_hash('admin123', PASSWORD_DEFAULT);
        $default_name = 'Administrator';
        
        $check_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_user->bind_param("s", $default_username);
        $check_user->execute();
        $result = $check_user->get_result();
        
        if ($result->num_rows == 0) {
            $insert_admin = $conn->prepare("INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, 'admin')");
            $insert_admin->bind_param("sss", $default_username, $default_password, $default_name);
            $insert_admin->execute();
        }
        
        return true;
    } else {
        return false;
    }
}

// Function to authenticate user
function authenticate($username, $password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, username, password, full_name, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    
    return false;
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

// Function to require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Function to logout
function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Function to get current user info
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'full_name' => $_SESSION['full_name'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}

// Initialize users table if needed
if (isset($_GET['init_users'])) {
    if (createUsersTable()) {
        echo "Users table created successfully!<br>";
        echo "Default admin user created:<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "<a href='login.php'>Go to Login</a>";
    } else {
        echo "Error creating users table: " . $conn->error;
    }
    exit();
}
?>
