<?php
require_once 'auth.php';
requireLogin();
require_once 'koneksi.php';

$page_title = "Tambah Data Absensi";

// Fetch employees for dropdown
$karyawan_result = $conn->query("SELECT id_karyawan, nama_karyawan FROM karyawan");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_absensi = $_POST['kode_absensi'];
    $id_karyawan = $_POST['id_karyawan'];
    $tanggal = $_POST['tanggal'];
    $jam_masuk = $_POST['jam_masuk'];
    $jam_pulang = $_POST['jam_pulang'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO absensi (kode_absensi, id_karyawan, tanggal, jam_masuk, jam_pulang, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $kode_absensi, $id_karyawan, $tanggal, $jam_masuk, $jam_pulang, $status);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data absensi berhasil ditambahkan.";
        header("Location: absensi.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }
}

ob_start();
?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Tambah Data Absensi</h6>
            </div>
            <div class="card-body">
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form action="absensi_tambah.php" method="POST">
                    <div class="mb-3">
                        <label for="kode_absensi" class="form-label">Kode Absensi</label>
                        <input type="text" class="form-control" id="kode_absensi" name="kode_absensi" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_karyawan" class="form-label">Karyawan</label>
                        <select class="form-control" id="id_karyawan" name="id_karyawan" required>
                            <option value="">Pilih Karyawan</option>
                            <?php while ($row = $karyawan_result->fetch_assoc()): ?>
                                <option value="<?php echo $row['id_karyawan']; ?>"><?php echo $row['nama_karyawan']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="jam_masuk" class="form-label">Jam Masuk</label>
                        <input type="time" class="form-control" id="jam_masuk" name="jam_masuk">
                    </div>
                    <div class="mb-3">
                        <label for="jam_pulang" class="form-label">Jam Pulang</label>
                        <input type="time" class="form-control" id="jam_pulang" name="jam_pulang">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Hadir">Hadir</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alpha">Alpha</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="absensi.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
