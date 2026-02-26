<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

/* =========================
   SIMPAN TRANSAKSI KELUAR
========================= */
if (isset($_POST['simpan'])) {

    if (empty($_POST['id_barang']) || empty($_POST['jumlah'])) {
        header("Location: transaksi_keluar.php");
        exit;
    }

    $id_barang = (int) $_POST['id_barang'];
    $jumlah    = (int) $_POST['jumlah'];
    $tanggal   = date('Y-m-d H:i:s');

    // Ambil harga & stok barang
    $cek = mysqli_query($conn, "SELECT harga, stok FROM barang WHERE id_barang = $id_barang");
    if (mysqli_num_rows($cek) == 0) {
        header("Location: transaksi_keluar.php");
        exit;
    }

    $data = mysqli_fetch_assoc($cek);

    // Validasi stok
    if ($jumlah <= 0 || $data['stok'] < $jumlah) {
        header("Location: transaksi_keluar.php?error=stok");
        exit;
    }

    $total = $data['harga'] * $jumlah;

    // Simpan transaksi (keluar)
    mysqli_query($conn, "INSERT INTO transaksi 
        (id_barang, jumlah, total, tanggal, jenis_transaksi)
        VALUES ($id_barang, $jumlah, $total, '$tanggal', 'keluar')");

    // Kurangi stok barang
    mysqli_query($conn, "UPDATE barang 
        SET stok = stok - $jumlah 
        WHERE id_barang = $id_barang");

    header("Location: transaksi_keluar.php?success=1");
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
        <li><a href="barang.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Barang</a></li>
        <li><a href="transaksi.php" class="nav-link"><i class="bi bi-receipt me-2"></i>Transaksi Masuk</a></li>
        <li><a href="transaksi_keluar.php" class="nav-link active"><i class="bi bi-receipt me-2"></i>Transaksi Keluar</a></li>
        <li><a href="profil.php" class="nav-link"><i class="bi bi-person me-2"></i>Profil</a></li>
        <li class="mt-4"><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
    </ul>
</div>

<!-- CONTENT -->
<div class="flex-grow-1 p-4">

<h4 class="fw-bold mb-3">Dashboard Transaksi Keluar</h4>

<!-- NOTIFIKASI -->
<?php if (isset($_GET['success'])) { ?>
<div class="alert alert-success">Transaksi berhasil disimpan</div>
<?php } ?>

<?php if (isset($_GET['error'])) { ?>
<div class="alert alert-danger">Stok tidak mencukupi</div>
<?php } ?>

<!-- FORM TRANSAKSI -->
<div class="card shadow-sm mb-4">
<div class="card-body">
<form method="POST" class="row g-3">

    <div class="col-md-5">
        <label class="form-label">Barang</label>
        <select name="id_barang" class="form-select" required>
            <option value="">-- Pilih Barang --</option>
            <?php
            $barang = mysqli_query($conn, "SELECT * FROM barang WHERE stok > 0");
            while ($b = mysqli_fetch_assoc($barang)) {
                echo "<option value='{$b['id_barang']}'>
                        {$b['nama_barang']} (Stok: {$b['stok']})
                      </option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Jumlah Keluar</label>
        <input type="number" name="jumlah" class="form-control" min="1" required>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" name="simpan" class="btn btn-danger w-100">
            <i class="bi bi-box-arrow-up"></i> Simpan
        </button>
    </div>

</form>
</div>
</div>

<!-- RIWAYAT TRANSAKSI -->
<div class="card shadow-sm">
<div class="card-body table-responsive">
<table class="table table-bordered align-middle">
<thead class="table-dark">
<tr>
    <th>No</th>
    <th>Nama Barang</th>
    <th>Jumlah Keluar</th>
    <th>Total</th>
    <th>Tanggal</th>
</tr>
</thead>
<tbody>

<?php
$no = 1;
$q = mysqli_query($conn, "
    SELECT t.*, b.nama_barang
    FROM transaksi t
    JOIN barang b ON t.id_barang = b.id_barang
    WHERE t.jenis_transaksi = 'keluar'
    ORDER BY t.id_transaksi DESC
");

while ($d = mysqli_fetch_assoc($q)) {
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $d['nama_barang']; ?></td>
    <td><?= $d['jumlah']; ?></td>
    <td>Rp<?= number_format($d['total'],0,',','.'); ?></td>
    <td><?= date('d-m-Y H:i', strtotime($d['tanggal'])); ?></td>
</tr>
<?php } ?>

</tbody>
</table>
</div>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
                                                   