<?php
require_once 'auth.php';
requireLogin();

$current_user = getCurrentUser();
$page_title = 'Detail Karyawan - Sistem Penggajian';

// Ensure only admin can access this page
if ($current_user['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

require_once 'koneksi.php';

$target_id_karyawan = $_GET['id'] ?? null;
$karyawan_data = null;
$absensi_data = [];
$gaji_data = null;
$error_message = '';

if (!$target_id_karyawan) {
    $error_message = "ID Karyawan tidak ditemukan.";
} else {
    try {
        // Fetch Karyawan details
        $stmt_karyawan = $pdo->prepare("SELECT * FROM karyawan WHERE id_karyawan = ?");
        $stmt_karyawan->execute([$target_id_karyawan]);
        $karyawan_data = $stmt_karyawan->fetch(PDO::FETCH_ASSOC);

        if (!$karyawan_data) {
            $error_message = "Data karyawan tidak ditemukan.";
        } else {
            // Fetch Absensi data
            $stmt_absensi = $pdo->prepare("SELECT * FROM absensi WHERE id_karyawan = ? ORDER BY tanggal DESC");
            $stmt_absensi->execute([$target_id_karyawan]);
            $absensi_data = $stmt_absensi->fetchAll(PDO::FETCH_ASSOC);

            // Fetch Gaji data (latest one)
            $stmt_gaji = $pdo->prepare("
                SELECT g.*, k.nama_karyawan, j.nama_jabatan 
                FROM gaji g
                JOIN karyawan k ON g.id_karyawan = k.id_karyawan
                JOIN jabatan j ON k.kode_jabatan = j.kode_jabatan
                WHERE g.id_karyawan = ?
                ORDER BY g.created_at DESC
                LIMIT 1
            ");
            $stmt_gaji->execute([$target_id_karyawan]);
            $gaji_data = $stmt_gaji->fetch(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

ob_start();
?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Detail Karyawan: <?php echo htmlspecialchars($karyawan_data['nama_karyawan'] ?? 'N/A'); ?></h6>
                <a href="karyawan.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Karyawan
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php else: ?>
                    <h6>Informasi Karyawan</h6>
                    <p><strong>ID Karyawan:</strong> <?php echo htmlspecialchars($karyawan_data['id_karyawan']); ?></p>
                    <p><strong>Nama Karyawan:</strong> <?php echo htmlspecialchars($karyawan_data['nama_karyawan']); ?></p>
                    <p><strong>TTL:</strong> <?php echo htmlspecialchars($karyawan_data['ttl']); ?></p>
                    <p><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars($karyawan_data['jenis_kelamin']); ?></p>
                    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($karyawan_data['alamat']); ?></p>
                    <p><strong>No HP:</strong> <?php echo htmlspecialchars($karyawan_data['no_hp']); ?></p>
                    <hr>

                    <h6>Data Absensi</h6>
                    <?php if (empty($absensi_data)): ?>
                        <p>Belum ada data absensi untuk karyawan ini.</p>
                    <?php else: ?>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jam Masuk</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jam Pulang</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($absensi_data as $absensi): ?>
                                        <tr>
                                            <td><p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($absensi['tanggal']); ?></p></td>
                                            <td><p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($absensi['jam_masuk']); ?></p></td>
                                            <td><p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($absensi['jam_pulang']); ?></p></td>
                                            <td><p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($absensi['status']); ?></p></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    <hr>

                    <h6>Data Slip Gaji (Terbaru)</h6>
                    <?php if (!$gaji_data): ?>
                        <p>Belum ada data slip gaji untuk karyawan ini.</p>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Kode Gaji:</strong> <?php echo htmlspecialchars($gaji_data['kode_gaji']); ?></p>
                                <p><strong>Tanggal Gaji:</strong> <?php echo date('d F Y', strtotime($gaji_data['created_at'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Gaji Pokok:</strong> Rp <?php echo number_format($gaji_data['gaji_pokok'], 0, ',', '.'); ?></p>
                                <p><strong>Tunjangan:</strong> Rp <?php echo number_format($gaji_data['tunjangan'], 0, ',', '.'); ?></p>
                                <p><strong>Bonus:</strong> Rp <?php echo number_format($gaji_data['bonus'], 0, ',', '.'); ?></p>
                            </div>
                        </div>
                        <h4><strong>Total Gaji:</strong> Rp <?php echo number_format($gaji_data['total_gaji'], 0, ',', '.'); ?></h4>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
