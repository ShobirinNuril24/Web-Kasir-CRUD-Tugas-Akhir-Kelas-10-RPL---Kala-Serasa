<?php
session_start(); // ← tambah ini
include('../koneksi.php');

$id   = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT gambar_menu FROM menu WHERE id='$id'");
$row  = mysqli_fetch_assoc($data);

$gambar = $row['gambar_menu'];

// Hapus file gambar dari folder assets/
if ($gambar && file_exists('../assets/' . $gambar)) {
    unlink('../assets/' . $gambar);
}

// Hapus data dari database
$query  = "DELETE FROM menu WHERE id='$id'";
$result = mysqli_query($koneksi, $query);

if ($result) {
    // ← ganti alert() dengan session toast
    $_SESSION['toast'] = [
        'type'    => 'success',
        'message' => 'Produk berhasil dihapus!'
    ];
    header("Location: ../index.php");
    exit;
} else {
    die("Query Error: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
}
