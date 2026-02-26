<?php
include 'koneksi.php';

$username = 'admin';
$password = 'password123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password') ON DUPLICATE KEY UPDATE password='$hashed_password'";

if (mysqli_query($conn, $query)) {
    echo "User inserted or updated successfully. Username: admin, Password: password123";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
