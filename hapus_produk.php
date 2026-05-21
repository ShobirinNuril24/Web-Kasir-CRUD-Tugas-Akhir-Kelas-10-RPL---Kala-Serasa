<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login');
    exit;
}
if ($_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$id   = (int)$_GET['id'];
$menu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT gambar_menu FROM menu WHERE id=$id"));

// Hapus file gambar dari folder assets/ jika ada
if ($menu && !empty($menu['gambar_menu']) && file_exists('assets/' . $menu['gambar_menu'])) {
    unlink('assets/' . $menu['gambar_menu']);
}

$result = mysqli_query($koneksi, "DELETE FROM menu WHERE id=$id");
if($result) {
    echo "<script>alert('Data berhasil dihapus.');window.location='index.php';</script>";
} else {
    die("Query Error : ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
}
?>