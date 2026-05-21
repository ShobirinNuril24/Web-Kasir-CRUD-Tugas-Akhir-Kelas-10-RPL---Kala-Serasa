<?php
include('koneksi.php');
$query = "SELECT * FROM menu LIMIT 1";
$result = mysqli_query($koneksi, $query);
if ($result) {
    echo "Koneksi dan query berhasil.";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>