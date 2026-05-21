<?php
session_start();
include('../koneksi.php');

// CEK LOGIN
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

// CEK ROLE ADMIN
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user_home.php');
    exit;
}

// CEK APAKAH DATA DIKIRIM DARI FORM
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// AMBIL DATA DARI FORM
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$stok_tambah = isset($_POST['stok_tambah']) ? (int) $_POST['stok_tambah'] : 0;

// VALIDASI DATA
if ($id <= 0 || $stok_tambah <= 0) {
    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => 'Jumlah stok tidak valid'
    ];

    header('Location: ../index.php');
    exit;
}

// CEK MENU BERDASARKAN ID
$query_menu = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = '$id'");

if (!$query_menu || mysqli_num_rows($query_menu) == 0) {
    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => 'Menu tidak ditemukan'
    ];

    header('Location: ../index.php');
    exit;
}

$data = mysqli_fetch_assoc($query_menu);

// HITUNG STOK BARU
$stok_lama = (int) $data['stok'];
$stok_baru = $stok_lama + $stok_tambah;

// KALAU STOK BERTAMBAH, STATUS JADI TERSEDIA
$status_baru = $stok_baru > 0 ? 'tersedia' : 'habis';

// UPDATE STOK KE DATABASE
$query_update = mysqli_query($koneksi, "
    UPDATE menu 
    SET stok = '$stok_baru',
        status = '$status_baru'
    WHERE id = '$id'
");

if ($query_update) {
    $_SESSION['toast'] = [
        'type' => 'success',
        'message' => 'Stok berhasil ditambahkan'
    ];
} else {
    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => 'Gagal menambahkan stok: ' . mysqli_error($koneksi)
    ];
}

// KEMBALI KE HALAMAN ADMIN
header('Location: ../index.php');
exit;
