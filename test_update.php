<?php
include('koneksi.php');

// Simulasi POST data
$_POST['id'] = 1;
$_POST['nama_menu'] = 'Test Menu';
$_POST['deskripsi'] = 'Test Desc';
$_POST['harga_menu'] = 10000;
$_POST['status'] = 'tersedia';
$_POST['gambar_menu'] = ''; // No image

// Copy from proses_edit.php logic
$id = $_POST['id'];
$nama_produk = $_POST['nama_menu'];
$deskripsi = $_POST['deskripsi'];
$harga_menu = $_POST['harga_menu'];
$status = $_POST['status'];
$gambar_produk = $_POST['gambar_menu'];

$query = "UPDATE menu SET nama_menu='$nama_produk', deskripsi='$deskripsi', harga_menu='$harga_menu', status='$status'";

$query .= " WHERE id='$id'";

$result = mysqli_query($koneksi, $query);

if($result) {
    echo "Update berhasil.";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>