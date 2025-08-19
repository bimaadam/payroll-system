# Sistem Penggajian - Payroll Management System

## 📋 Overview

A complete payroll web application built with PHP, AJAX, HTML, and CSS using the Soft UI Dashboard template. The system manages job positions, employees, and salary data with a clean, separated architecture.

## 🏗️ Architecture - Separated Logic Structure

### **Index & Dashboard**

- **`index.php`** - Simple redirect to dashboard
- **`dashboard.php`** - Main dashboard with statistics and overview

### **Module Structure**

Each module (Jabatan, Karyawan, Gaji) has separated files for different actions:

#### **Jabatan (Job Positions)**

- **`jabatan.php`** - List all positions with view/delete actions
- **`jabatan_tambah.php`** - Add new position form
- **`jabatan_edit.php`** - Edit existing position
- **`jabatan_hapus.php`** - Delete position with confirmation

#### **Karyawan (Employees)**

- **`karyawan.php`** - List all employees with view/delete actions
- **`karyawan_tambah.php`** - Add new employee form
- **`karyawan_edit.php`** - Edit existing employee
- **`karyawan_hapus.php`** - Delete employee with confirmation

#### **Gaji (Salaries)**

- **`gaji.php`** - List all salary data with view/delete actions
- **`gaji_tambah.php`** - Add new salary data form
- **`gaji_edit.php`** - Edit existing salary data
- **`gaji_hapus.php`** - Delete salary data with confirmation

## 🚀 Setup Instructions

### 1. Database Setup

Run the setup script to create database and tables:

```
http://localhost/penggajian/setup.php
```

### 2. Default Login Credentials

- **Username:** admin
- **Password:** admin123

### 3. Features

- ✅ **Authentication System** - Secure login/logout
- ✅ **CRUD Operations** - Complete Create, Read, Update, Delete for all modules
- ✅ **Responsive Design** - Works on all devices
- ✅ **Local Assets** - No external dependencies
- ✅ **Auto-generated IDs** - Automatic ID generation for all records
- ✅ **Form Validation** - Client and server-side validation
- ✅ **SweetAlert2** - Beautiful confirmation dialogs

## 📁 File Structure

```
/penggajian/
├── index.php                 # Redirect to dashboard
├── dashboard.php             # Main dashboard
├── login.php                 # Login page
├── logout.php                # Logout handler
├── setup.php                 # Database setup script
├──
├── jabatan.php               # Position listing
├── jabatan_tambah.php        # Add position
├── jabatan_edit.php          # Edit position
├── jabatan_hapus.php         # Delete position
├──
├── karyawan.php              # Employee listing
├── karyawan_tambah.php       # Add employee
├── karyawan_edit.php         # Edit employee
├── karyawan_hapus.php        # Delete employee
├──
├── gaji.php                  # Salary listing
├── gaji_tambah.php           # Add salary
├── gaji_edit.php             # Edit salary
├── gaji_hapus.php            # Delete salary
├──
├── api/                      # REST API endpoints
│   ├── dashboard_stats.php   # Dashboard statistics
│   ├── jabatan.php           # Position CRUD API
│   ├── karyawan.php          # Employee CRUD API
│   └── gaji.php              # Salary CRUD API
├──
├── includes/                 # Reusable components
│   ├── layout.php            # Main layout template
│   ├── sidebar.php           # Navigation sidebar
│   └── header.php            # Page header
├──
├── assets/                   # Local assets
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript files
│   └── img/                  # Images
└──
└── auth.php                  # Authentication functions
└── koneksi.php               # Database connection
```

## 🎯 Key Benefits

### **1. Clean Separation of Concerns**

- Each action has its own dedicated file
- Easy to maintain and debug
- Clear URL structure for each operation

### **2. Better User Experience**

- Direct page navigation instead of modals
- Proper browser history and bookmarkable URLs
- Fast loading with optimized structure

### **3. Scalable Architecture**

- Easy to add new modules or features
- Modular design allows independent development
- Consistent patterns across all modules

### **4. Enhanced Security**

- Authentication required for all pages
- SQL injection protection with prepared statements
- XSS protection with proper escaping

## 🔧 Technical Details

### **Database Tables**

- **`jabatan`** - Job positions (kode_jabatan, nama_jabatan)
- **`karyawan`** - Employees (id_karyawan, nama_karyawan, ttl, jenis_kelamin, alamat, no_hp, kode_jabatan)
- **`gaji`** - Salaries (kode_gaji, id_karyawan, gaji_pokok, tunjangan, bonus)
- **`users`** - System users (username, password, role)

### **Technologies Used**

- **Backend:** PHP 7.4+, MySQL
- **Frontend:** HTML5, CSS3, JavaScript, jQuery
- **UI Framework:** Soft UI Dashboard
- **Icons:** Font Awesome, Nucleo Icons
- **Alerts:** SweetAlert2

## 📝 Usage Examples

### **Adding New Employee**

1. Navigate to `karyawan.php`
2. Click "Tambah Karyawan" button
3. Fill form in `karyawan_tambah.php`
4. Submit to save and return to listing

### **Editing Salary Data**

1. Navigate to `gaji.php`
2. Click edit button on desired record
3. Modify data in `gaji_edit.php`
4. Submit to update and return to listing

### **Deleting Position**

1. Navigate to `jabatan.php`
2. Click delete button on desired record
3. Confirm deletion in `jabatan_hapus.php`
4. Record deleted and redirected to listing

## 🎨 UI Features

- **Modern Design** - Clean, professional interface
- **Responsive Layout** - Works on desktop, tablet, mobile
- **Loading States** - Visual feedback during operations
- **Form Validation** - Real-time validation with error messages
- **Confirmation Dialogs** - Beautiful SweetAlert2 confirmations
- **Auto-calculations** - Real-time total salary calculation

---

## Preview

<img src="assets/img/login-bg.png" alt="image"></img>

**Developed with Bima Adam ❤️ using PHP and Soft UI Dashboard**
