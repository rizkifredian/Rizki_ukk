<?php
session_start();
include 'koneksi.php'; 


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$error = "";


if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    
    if ($username === '' || $password === '') {
        $error = "Username dan password wajib diisi";
    } else {
        $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        $data  = mysqli_fetch_assoc($query);

      
        if ($data && password_verify($password, $data['password'])) {
            $_SESSION['username'] = $data['username'];
            header("Location: home.php");
            exit;
        } else {
            $error = "Username atau password salah";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>UKK </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: url('img/bg.jpg') no-repeat center center; background-size: cover;">

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">

<?php if (!isset($_SESSION['username'])): ?>

    <div class="card shadow-lg p-4" style="width:400px;">
        <h4 class="text-center mb-2">Login</h4>
        <p class="text-center text-muted mb-3">Masukkan akun Anda</p>

        <?php if ($error != ""): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>

<?php else: ?>
    <div class="card shadow p-4" style="width:500px;">
        <h4>Dashboard</h4>
        <hr>
        <p>Login sebagai <strong><?= $_SESSION['username']; ?></strong></p>
        <a href="?logout=true" class="btn btn-danger btn-sm">Logout</a>
    </div>
<?php endif; ?>

</div>
</body>
</html>
