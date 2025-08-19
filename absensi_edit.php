<?php
require_once 'auth.php';
requireLogin();
require_once 'koneksi.php';

$page_title = "Edit Data Absensi";

if (!isset($_GET['id'])) {
    header("Location: absensi.php");
    exit();
}

$kode_absensi = $_GET['id'];

// Fetch attendance data
$stmt = $conn->prepare("SELECT * FROM absensi WHERE kode_absensi = ?");
$stmt->bind_param("s", $kode_absensi);
$stmt->execute();
$result = $stmt->get_result();
$absensi = $result->fetch_assoc();

if (!$absensi) {
    header("Location: absensi.php");
    exit();
}

// Fetch employees for dropdown
$karyawan_result = $conn->query("SELECT id_karyawan, nama_karyawan FROM karyawan");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_karyawan = $_POST['id_karyawan'];
    $tanggal = $_POST['tanggal'];
    $jam_masuk = $_POST['jam_masuk'];
    $jam_pulang = $_POST['jam_pulang'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE absensi SET id_karyawan = ?, tanggal = ?, jam_masuk = ?, jam_pulang = ?, status = ? WHERE kode_absensi = ?");
    $stmt->bind_param("ssssss", $id_karyawan, $tanggal, $jam_masuk, $jam_pulang, $status, $kode_absensi);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data absensi berhasil diperbarui.";
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
                <h6>Edit Data Absensi</h6>
            </div>
            <div class="card-body">
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form action="absensi_edit.php?id=<?php echo $kode_absensi; ?>" method="POST">
                    <div class="mb-3">
                        <label for="kode_absensi" class="form-label">Kode Absensi</label>
                        <input type="text" class="form-control" id="kode_absensi" name="kode_absensi" value="<?php echo $absensi['kode_absensi']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="id_karyawan" class="form-label">Karyawan</label>
                        <select class="form-control" id="id_karyawan" name="id_karyawan" required>
                            <option value="">Pilih Karyawan</option>
                            <?php while ($row = $karyawan_result->fetch_assoc()): ?>
                                <option value="<?php echo $row['id_karyawan']; ?>" <?php echo ($row['id_karyawan'] == $absensi['id_karyawan']) ? 'selected' : ''; ?>><?php echo $row['nama_karyawan']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $absensi['tanggal']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jam_masuk" class="form-label">Jam Masuk</label>
                        <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" value="<?php echo $absensi['jam_masuk']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="jam_pulang" class="form-label">Jam Pulang</label>
                        <input type="time" class="form-control" id="jam_pulang" name="jam_pulang" value="<?php echo $absensi['jam_pulang']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Hadir" <?php echo ($absensi['status'] == 'Hadir') ? 'selected' : ''; ?>>Hadir</option>
                            <option value="Sakit" <?php echo ($absensi['status'] == 'Sakit') ? 'selected' : ''; ?>>Sakit</option>
                            <option value="Izin" <?php echo ($absensi['status'] == 'Izin') ? 'selected' : ''; ?>>Izin</option>
                            <option value="Alpha" <?php echo ($absensi['status'] == 'Alpha') ? 'selected' : ''; ?>>Alpha</option>
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
