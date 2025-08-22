<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Slip Gaji - Sistem Penggajian';

require_once 'koneksi.php';

$gaji_data = null;
$error_message = '';

// Admin can view any slip by ID. A 'karyawan' can only view their own.
if ($current_user['role'] === 'admin' && isset($_GET['id'])) {
    // Admin is viewing a specific payslip
    try {
        $kode_gaji = $_GET['id'];
        $stmt = $pdo->prepare("
            SELECT g.*, k.nama_karyawan, j.nama_jabatan 
            FROM gaji g
            JOIN karyawan k ON g.id_karyawan = k.id_karyawan
            JOIN jabatan j ON k.kode_jabatan = j.kode_jabatan
            WHERE g.kode_gaji = ?
        ");
        $stmt->execute([$kode_gaji]);
        $gaji_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$gaji_data) {
            $error_message = "Data slip gaji dengan kode " . htmlspecialchars($kode_gaji) . " tidak ditemukan.";
        }

    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
} elseif ($current_user['role'] === 'karyawan') {
    // Karyawan is viewing their own latest payslip
    if (isset($current_user['id_karyawan'])) {
        try {
            $stmt = $pdo->prepare("
                SELECT g.*, k.nama_karyawan, j.nama_jabatan 
                FROM gaji g
                JOIN karyawan k ON g.id_karyawan = k.id_karyawan
                JOIN jabatan j ON k.kode_jabatan = j.kode_jabatan
                WHERE g.id_karyawan = ?
                ORDER BY g.created_at DESC
                LIMIT 1
            ");
            $stmt->execute([$current_user['id_karyawan']]);
            $gaji_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$gaji_data) {
                $error_message = "Data slip gaji belum tersedia.";
            }

        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    } else {
        $error_message = "ID Karyawan tidak ditemukan untuk pengguna ini.";
    }
} else {
    // Redirect if user is not authorized or params are missing
    header('Location: dashboard.php');
    exit();
}

// Start output buffering for content
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card mb-4" id="slip-gaji-card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <?php 
                        if ($current_user['role'] === 'admin' && !empty($gaji_data['nama_karyawan'])) {
                            echo 'Slip Gaji: ' . htmlspecialchars($gaji_data['nama_karyawan']);
                        } else {
                            echo 'Slip Gaji Anda';
                        }
                    ?>
                </h6>
                <button class="btn btn-primary btn-sm" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
            <div class="card-body" id="slip-gaji-content">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger text-white" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php elseif ($gaji_data): 
                    $total_gaji = $gaji_data['gaji_pokok'] + $gaji_data['tunjangan'] + $gaji_data['bonus'];
                ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Nama Karyawan:</strong><br> <?php echo htmlspecialchars($gaji_data['nama_karyawan']); ?></p>
                            <p class="text-sm mb-2"><strong>Jabatan:</strong><br> <?php echo htmlspecialchars($gaji_data['nama_jabatan']); ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="text-sm mb-2"><strong>Kode Gaji:</strong><br> <?php echo htmlspecialchars($gaji_data['kode_gaji']); ?></p>
                            <p class="text-sm mb-2"><strong>Tanggal:</strong><br> <?php echo date('d F Y', strtotime($gaji_data['created_at'])); ?></p>
                        </div>
                    </div>
                    <hr class="horizontal dark my-3">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
                                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Gaji Pokok</td>
                                    <td class="text-end">Rp <?php echo number_format($gaji_data['gaji_pokok'], 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td>Tunjangan</td>
                                    <td class="text-end">Rp <?php echo number_format($gaji_data['tunjangan'], 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td>Bonus</td>
                                    <td class="text-end">Rp <?php echo number_format($gaji_data['bonus'], 0, ',', '.'); ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-dark">Total Gaji</th>
                                    <th class="text-end text-dark h5">Rp <?php echo number_format($total_gaji, 0, ',', '.'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <p class="text-xs mt-4">Ini adalah slip gaji yang dibuat secara otomatis oleh sistem. Harap simpan sebagai referensi Anda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
  body, .main-content, .container-fluid {
    background-color: white !important;
  }
  .main-content * {
    visibility: hidden;
  }
  #slip-gaji-card, #slip-gaji-card * {
    visibility: visible;
  }
  #slip-gaji-card {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    box-shadow: none !important;
    border: none !important;
  }
  .card-header button {
      display: none;
  }
  .navbar, .sidenav, .fixed-plugin {
      display: none !important;
  }
}
</style>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
