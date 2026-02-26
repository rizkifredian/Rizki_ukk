<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Barang
$qBarang = mysqli_query($conn, "SELECT COUNT(*) AS total_barang FROM barang");
$barang  = mysqli_fetch_assoc($qBarang);

// Total Stok
$qTotalStok = mysqli_query($conn, "SELECT SUM(stok) AS total_stok FROM barang");
$totalStok = mysqli_fetch_assoc($qTotalStok);

// Stok Habis
$qStokHabis = mysqli_query($conn, "SELECT COUNT(*) AS stok_habis FROM barang WHERE stok = 0");
$stokHabis = mysqli_fetch_assoc($qStokHabis);

// Transaksi Masuk
$qMasuk = mysqli_query($conn, "SELECT COUNT(*) AS total_masuk FROM transaksi WHERE jenis_transaksi = 'masuk'");
$masuk = mysqli_fetch_assoc($qMasuk);

// Transaksi Keluar
$qKeluar = mysqli_query($conn, "SELECT COUNT(*) AS total_keluar FROM transaksi WHERE jenis_transaksi = 'keluar'");
$keluar = mysqli_fetch_assoc($qKeluar);

// Total Transaksi
$qTotalTrans = mysqli_query($conn, "SELECT COUNT(*) AS total_trans FROM transaksi");
$totalTrans = mysqli_fetch_assoc($qTotalTrans);

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>UKK</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{background:#f4f6f9;}
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
.card-stat{
    border:none;
    border-radius:16px;
}
</style>
</head>

<body>
<div class="d-flex">

<!-- SIDEBAR -->
<div class="sidebar p-3">
    <h5 class="text-white text-center mb-4">UKK APP</h5>
    <ul class="nav flex-column">
        <li><a href="home.php" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
        <li><a href="barang.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Barang</a></li>
        <li><a href="transaksi.php" class="nav-link"><i class="bi bi-receipt me-2"></i>Transaksi Masuk</a></li>
        <li><a href="transaksi_keluar.php" class="nav-link"><i class="bi bi-receipt me-2"></i>Transaksi Keluar</a></li>
        <li><a href="profil.php" class="nav-link"><i class="bi bi-person me-2"></i>Profil</a></li>
        <li class="mt-4"><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
    </ul>
</div>

<!-- CONTENT -->
<div class="flex-grow-1 p-4">

    <div class="d-flex justify-content-between mb-3">
        <h4 class="fw-bold">Dashboard</h4>
        <span class="text-muted"><?= date('d F Y'); ?></span>
    </div>

    <p class="text-muted">
        Selamat datang, <strong><?= $_SESSION['username']; ?></strong>
    </p>

    <!-- STAT -->
    <div class="row g-4 mt-3">

        <div class="col-md-3">
            <div class="card card-stat shadow-sm bg-primary text-white">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Barang</small>
                        <h2><?= $barang['total_barang']; ?></h2>
                    </div>
                    <i class="bi bi-box fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat shadow-sm bg-success text-white">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Transaksi Masuk</small>
                        <h2><?= $masuk['total_masuk']; ?></h2>
                    </div>
                    <i class="bi bi-arrow-down-circle fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat shadow-sm bg-danger text-white">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Transaksi Keluar</small>
                        <h2><?= $keluar['total_keluar']; ?></h2>
                    </div>
                    <i class="bi bi-arrow-up-circle fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat shadow-sm bg-dark text-white">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Total Transaksi</small>
                        <h2><?= $totalTrans['total_trans']; ?></h2>
                    </div>
                    <i class="bi bi-clipboard-data fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

    </div>

</div>
</div>
</body>
</html>
