<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_transaction'])) {
    $id_barang = mysqli_real_escape_string($conn, $_POST['id_barang']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $jenis_transaksi = 'masuk'; 
    $tanggal = date('Y-m-d H:i:s');

    
    $item_query = "SELECT harga, stok FROM barang WHERE id_barang = '$id_barang'";
    $item_result = mysqli_query($conn, $item_query);
    $item = mysqli_fetch_assoc($item_result);

    $total = $item['harga'] * $jumlah;

    $query = "INSERT INTO transaksi (id_barang, jumlah, total, tanggal, jenis_transaksi) VALUES ('$id_barang', '$jumlah', '$total', '$tanggal', '$jenis_transaksi')";
    mysqli_query($conn, $query);

  
    $new_stok = $item['stok'] + $jumlah;
    mysqli_query($conn, "UPDATE barang SET stok = '$new_stok' WHERE id_barang = '$id_barang'");

    header("Location: transaksi.php");
    exit;
}

$query = "SELECT t.*, b.nama_barang FROM transaksi t JOIN barang b ON t.id_barang = b.id_barang WHERE t.jenis_transaksi = 'masuk' ORDER BY t.tanggal DESC";
$result = mysqli_query($conn, $query);

$items_query = "SELECT * FROM barang";
$items_result = mysqli_query($conn, $items_query);

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
        <li><a href="home.php" class="nav-link "><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
        <li><a href="barang.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Barang</a></li>
        <li><a href="transaksi.php" class="nav-link active active"><i class="bi bi-receipt me-2"></i>Transaksi Masuk</a></li>
        <li><a href="transaksi_keluar.php" class="nav-link"><i class="bi bi-receipt me-2"></i>Transaksi Keluar</a></li>
        <li><a href="profil.php" class="nav-link"><i class="bi bi-person me-2"></i>Profil</a></li>
        <li class="mt-4"><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
    </ul>
</div>
    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <div class="d-flex justify-content-between mb-3">
            <h4 class="fw-bold">Dashboard Transaksi</h4>
            <span class="text-muted"><?= date('d F Y'); ?></span>
        </div>

        <p class="text-muted">
            Selamat datang, <strong><?= $_SESSION['username']; ?></strong>
        </p>

        <!-- Add Transaction Form -->
        <div class="card mb-4">
            <div class="card-header">Tambah Transaksi Masuk</div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="id_barang" class="form-control" required>
                                <option value="">Pilih Barang</option>
                                <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                                    <option value="<?php echo $item['id_barang']; ?>"><?php echo $item['nama_barang']; ?> (Stok: <?php echo $item['stok']; ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="add_transaction" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header">Daftar Transaksi Masuk</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id_transaksi']; ?></td>
                            <td><?php echo $row['nama_barang']; ?></td>
                            <td><?php echo $row['jumlah']; ?></td>
                            <td>Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                            <td><?php echo $row['tanggal']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</body>
</html>
