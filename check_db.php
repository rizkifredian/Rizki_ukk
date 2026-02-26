<?php
include 'koneksi.php';

// Add jumlah column to transaksi table if it doesn't exist
$query = "ALTER TABLE transaksi ADD COLUMN jumlah INT NOT NULL DEFAULT 1";
if (mysqli_query($conn, $query)) {
    echo "Column 'jumlah' added to transaksi table.\n";
} else {
    echo "Column 'jumlah' already exists or error: " . mysqli_error($conn) . "\n";
}

// Rename total_harga to total if needed
$query = "ALTER TABLE transaksi CHANGE total_harga total INT";
if (mysqli_query($conn, $query)) {
    echo "Column 'total_harga' renamed to 'total'.\n";
} else {
    echo "Column already named 'total' or error: " . mysqli_error($conn) . "\n";
}

// Rename waktu to tanggal if needed
$query = "ALTER TABLE transaksi CHANGE waktu tanggal DATE";
if (mysqli_query($conn, $query)) {
    echo "Column 'waktu' renamed to 'tanggal'.\n";
} else {
    echo "Column already named 'tanggal' or error: " . mysqli_error($conn) . "\n";
}

echo "\nUpdated transaksi table structure:\n";
$result = mysqli_query($conn, "DESCRIBE transaksi");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\nSample data from transaksi table:\n";
$result = mysqli_query($conn, "SELECT * FROM transaksi LIMIT 5");
while ($row = mysqli_fetch_assoc($result)) {
    print_r($row);
    echo "\n";
}
?>
