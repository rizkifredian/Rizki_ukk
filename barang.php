<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

/* =========================
   TAMBAH BARANG
========================= */
if (isset($_POST['tambah'])) {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok  = (int) $_POST['stok'];
    $harga = (int) $_POST['harga'];

    mysqli_query($conn, "INSERT INTO barang (nama_barang, stok, harga)
        VALUES ('$nama', $stok, $harga)");

    header("Location: barang.php");
    exit;
}

/* =========================
   UPDATE BARANG
========================= */
if (isset($_POST['update'])) {
    $id    = (int) $_POST['id_barang'];
    $nama  = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok  = (int) $_POST['stok'];
    $harga = (int) $_POST['harga'];

    mysqli_query($conn, "UPDATE barang SET
        nama_barang='$nama',
        stok=$stok,
        harga=$harga
        WHERE id_barang=$id");

    header("Location: barang.php");
    exit;
}

/* =========================
   HAPUS BARANG
========================= */
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM barang WHERE id_barang=$id");
    header("Location: barang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>UKK</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }
.sidebar{
    width:250px;
    min-height:100vh;
    background:#1e293b;
}
.sidebar .nav-link{
    color:#cbd5e1;
    border-radius:8px;
    margin-bottom:6px;
}
.sidebar .nav-link:hover,
.sidebar .nav-link.active{
    background:#334155;
    color:#fff;
}
</style>
</head>

<body>
<div class="d-flex">

<!-- SIDEBAR -->
<div class="sidebar p-3">
    <h5 class="text-white text-center mb-4">UKK APP</h5>
    <ul class="nav flex-column">
        <li><a href="home.php" class="nav-link"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
        <li><a href="barang.php" class="nav-link active"><i class="bi bi-box-seam me-2"></i>Barang</a></li>
        <li><a href="transaksi.php" class="nav-link"><i class="bi bi-receipt me-2"></i>Transaksi Masuk</a></li>
        <li><a href="transaksi_keluar.php" class="nav-link"><i class="bi bi-receipt me-2"></i>Transaksi Keluar</a></li>
        <li><a href="profil.php" class="nav-link"><i class="bi bi-person me-2"></i>Profil</a></li>
        <li class="mt-4"><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
    </ul>
</div>

<!-- CONTENT -->
<div class="flex-grow-1 p-4">

<h4 class="fw-bold mb-3">Dashboard Barang</h4>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle"></i> Tambah Barang
</button>

<div class="card shadow-sm">
<div class="card-body table-responsive">
<table class="table table-bordered align-middle">
<thead class="table-dark">
<tr>
    <th>No</th>
    <th>Nama Barang</th>
    <th>Stok</th>
    <th>Harga</th>
    <th>Total</th>
    <th>Status</th>
    <th width="120">Aksi</th>
</tr>
</thead>
<tbody>

<?php
$no = 1;
$dataBarang = mysqli_query($conn, "SELECT * FROM barang");
while ($d = mysqli_fetch_assoc($dataBarang)) {

    $total = $d['stok'] * $d['harga'];
    if ($d['stok'] == 0) {
        $status = '<span class="badge bg-danger">Habis</span>';
    } elseif ($d['stok'] <= 10) {
        $status = '<span class="badge bg-warning text-dark">Menipis</span>';
    } else {
        $status = '<span class="badge bg-success">Tersedia</span>';
    }
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $d['nama_barang']; ?></td>
    <td><?= $d['stok']; ?></td>
    <td>Rp<?= number_format($d['harga'],0,',','.'); ?></td>
    <td>Rp<?= number_format($total,0,',','.'); ?></td>
    <td><?= $status; ?></td>
    <td class="text-center">
        <button class="btn btn-warning btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#edit<?= $d['id_barang']; ?>">
            <i class="bi bi-pencil"></i>
        </button>

        <a href="?hapus=<?= $d['id_barang']; ?>"
           onclick="return confirm('Hapus data?')"
           class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i>
        </a>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

</div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah">
<div class="modal-dialog">
<form method="POST" class="modal-content">
<div class="modal-header">
    <h5 class="modal-title">Tambah Barang</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <input type="text" name="nama_barang" class="form-control mb-2" placeholder="Nama Barang" required>
    <input type="number" name="stok" class="form-control mb-2" placeholder="Stok" required>
    <input type="number" name="harga" class="form-control" placeholder="Harga" required>
</div>
<div class="modal-footer">
    <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
</div>
</form>
</div>
</div>


<?php
$dataBarang = mysqli_query($conn, "SELECT * FROM barang");
while ($d = mysqli_fetch_assoc($dataBarang)) {
?>
<div class="modal fade" id="edit<?= $d['id_barang']; ?>">
<div class="modal-dialog">
<form method="POST" class="modal-content">
<input type="hidden" name="id_barang" value="<?= $d['id_barang']; ?>">

<div class="modal-header">
    <h5 class="modal-title">Edit Barang</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <input type="text" name="nama_barang" class="form-control mb-2"
           value="<?= $d['nama_barang']; ?>" required>
    <input type="number" name="stok" class="form-control mb-2"
           value="<?= $d['stok']; ?>" required>
    <input type="number" name="harga" class="form-control"
           value="<?= $d['harga']; ?>" required>
</div>

<div class="modal-footer">
    <button type="submit" name="update" class="btn btn-warning">Update</button>
</div>
</form>
</div>
</div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
