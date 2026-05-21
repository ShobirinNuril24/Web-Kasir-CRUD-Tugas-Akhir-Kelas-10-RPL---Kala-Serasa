<?php
session_start(); // ← WAJIB tambah ini di paling atas
include('../koneksi.php');

$nama_produk = $_POST['nama_produk'];
$deskripsi   = $_POST['deskripsi'];
$harga       = $_POST['harga'];
$status      = $_POST['status'];
$gambar      = $_FILES['gambar']['name'];

if ($gambar != "") {
    $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
    $x        = explode('.', $gambar);
    $ekstensi = strtolower(end($x));
    $file_tmp = $_FILES['gambar']['tmp_name'];
    $nama_gambar_baru = rand(1, 999) . '-' . $gambar;

    if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
        move_uploaded_file($file_tmp, '../assets/' . $nama_gambar_baru);

        $query  = "INSERT INTO menu(nama_menu, deskripsi, harga_menu, gambar_menu, status) 
                   VALUES('$nama_produk', '$deskripsi', '$harga', '$nama_gambar_baru', '$status')";
        $result = mysqli_query($koneksi, $query);

        if (!$result) {
            die("Query Error: " . mysqli_error($koneksi));
        } else {
         
            $_SESSION['toast'] = [
                'type'    => 'success',
                'message' => 'Produk berhasil ditambahkan!'
            ];
            header("Location: ../index.php");
            exit;
        }
    } else {
        $_SESSION['toast'] = [
            'type'    => 'error',
            'message' => 'Format gambar harus jpg, jpeg, atau png!'
        ];
        header("Location: ../tambah_produk.php");
        exit;
    }
} else {
    $query  = "INSERT INTO menu(nama_menu, deskripsi, harga_menu, status) 
               VALUES('$nama_produk', '$deskripsi', '$harga', '$status')";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die("Query Error: " . mysqli_error($koneksi));
    } else {
        // ← GANTI juga yang ini
        $_SESSION['toast'] = [
            'type'    => 'success',
            'message' => 'Produk berhasil ditambahkan!'
        ];
        header("Location: ../index.php");
        exit;
    }
}
