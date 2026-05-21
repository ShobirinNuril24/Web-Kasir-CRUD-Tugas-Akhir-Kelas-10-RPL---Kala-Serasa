<?php
session_start();
include("../koneksi.php");

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

// Pastikan ID ada
if (!isset($_POST['id']) || $_POST['id'] == '') {
    $_SESSION['toast'] = [
        'type'    => 'error',
        'message' => 'ID menu tidak ditemukan!'
    ];

    header("Location: ../index.php");
    exit;
}

$id           = mysqli_real_escape_string($koneksi, $_POST['id']);
$nama_produk  = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
$deskripsi    = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$harga_menu   = mysqli_real_escape_string($koneksi, $_POST['harga_menu']);
$status       = mysqli_real_escape_string($koneksi, $_POST['status']);

// Query dasar
$query = "UPDATE menu SET 
            nama_menu='$nama_produk',
            deskripsi='$deskripsi',
            harga_menu='$harga_menu',
            status='$status'";

// Cek apakah ada gambar baru
if (!empty($_FILES['gambar_menu']['name'])) {
    $gambar_produk = $_FILES['gambar_menu']['name'];
    $file_tmp      = $_FILES['gambar_menu']['tmp_name'];

    $ekstensi_diperbolehkan = ['png', 'jpg', 'jpeg'];
    $ekstensi = strtolower(pathinfo($gambar_produk, PATHINFO_EXTENSION));

    // Kalau format salah, balik ke halaman edit yang benar + tetap bawa ID
    if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {
        $_SESSION['toast'] = [
            'type'    => 'error',
            'message' => 'Format gambar harus jpg, jpeg, atau png!'
        ];

        header("Location: ../edit_menu.php?id=$id");
        exit;
    }

    // Bikin nama file baru
    $angka_acak = rand(1, 999);
    $nama_file_aman = preg_replace('/[^a-zA-Z0-9._-]/', '_', $gambar_produk);
    $nama_gambar_baru = $angka_acak . '-' . $nama_file_aman;

    // Upload gambar
    if (move_uploaded_file($file_tmp, '../assets/' . $nama_gambar_baru)) {
        $query .= ", gambar_menu='$nama_gambar_baru'";
    } else {
        $_SESSION['toast'] = [
            'type'    => 'error',
            'message' => 'Gagal upload gambar!'
        ];

        header("Location: ../edit_menu.php?id=$id");
        exit;
    }
}

// WHERE hanya sekali
$query .= " WHERE id='$id'";

// Eksekusi query
if (mysqli_query($koneksi, $query)) {
    $_SESSION['toast'] = [
        'type'    => 'success',
        'message' => 'Produk berhasil diperbarui!'
    ];

    header("Location: ../index.php");
    exit;
} else {
    $_SESSION['toast'] = [
        'type'    => 'error',
        'message' => 'Gagal memperbarui produk: ' . mysqli_error($koneksi)
    ];

    header("Location: ../edit_menu.php?id=$id");
    exit;
}
