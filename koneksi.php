<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'penggajian_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

// Also create PDO connection for modern PHP practices
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}

// Function to create database if not exists
function createDatabase() {
    global $host, $username, $password, $database;
    
    $temp_conn = new mysqli($host, $username, $password);
    
    if ($temp_conn->connect_error) {
        die("Connection failed: " . $temp_conn->connect_error);
    }
    
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    if ($temp_conn->query($sql) === TRUE) {
        echo "Database created successfully or already exists<br>";
    } else {
        echo "Error creating database: " . $temp_conn->error;
    }
    
    $temp_conn->close();
}

// Function to create tables
function createTables() {
    global $conn;
    
    // Create jabatan table
    $sql_jabatan = "CREATE TABLE IF NOT EXISTS jabatan (
        kode_jabatan VARCHAR(10) PRIMARY KEY,
        nama_jabatan VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    // Create karyawan table
    $sql_karyawan = "CREATE TABLE IF NOT EXISTS karyawan (
        id_karyawan VARCHAR(10) PRIMARY KEY,
        nama_karyawan VARCHAR(100) NOT NULL,
        ttl VARCHAR(100) NOT NULL,
        jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
        alamat TEXT NOT NULL,
        no_hp VARCHAR(15) NOT NULL,
        kode_jabatan VARCHAR(10),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (kode_jabatan) REFERENCES jabatan(kode_jabatan) ON DELETE SET NULL
    )";
    
    // Create gaji table
    $sql_gaji = "CREATE TABLE IF NOT EXISTS gaji (
        kode_gaji VARCHAR(10) PRIMARY KEY,
        id_karyawan VARCHAR(10),
        tunjangan DECIMAL(15,2) DEFAULT 0,
        bonus DECIMAL(15,2) DEFAULT 0,
        gaji_pokok DECIMAL(15,2) NOT NULL,
        total_gaji DECIMAL(15,2) GENERATED ALWAYS AS (gaji_pokok + tunjangan + bonus) STORED,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (id_karyawan) REFERENCES karyawan(id_karyawan) ON DELETE CASCADE
    )";
    
    if ($conn->query($sql_jabatan) === TRUE) {
        echo "Table jabatan created successfully<br>";
    } else {
        echo "Error creating table jabatan: " . $conn->error . "<br>";
    }
    
    if ($conn->query($sql_karyawan) === TRUE) {
        echo "Table karyawan created successfully<br>";
    } else {
        echo "Error creating table karyawan: " . $conn->error . "<br>";
    }
    
    if ($conn->query($sql_gaji) === TRUE) {
        echo "Table gaji created successfully<br>";
    } else {
        echo "Error creating table gaji: " . $conn->error . "<br>";
    }
}

// Initialize database and tables if needed
if (isset($_GET['init'])) {
    createDatabase();
    createTables();
    
    // Insert sample data
    $sample_jabatan = [
        ['JB001', 'Manager'],
        ['JB002', 'Staff Admin'],
        ['JB003', 'Developer'],
        ['JB004', 'Marketing']
    ];
    
    foreach ($sample_jabatan as $jabatan) {
        $stmt = $conn->prepare("INSERT IGNORE INTO jabatan (kode_jabatan, nama_jabatan) VALUES (?, ?)");
        $stmt->bind_param("ss", $jabatan[0], $jabatan[1]);
        $stmt->execute();
    }
    
    echo "Sample data inserted successfully<br>";
    echo "<a href='index.php'>Go to Application</a>";
}
?>
