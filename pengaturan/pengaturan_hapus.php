<?php
session_start();
include(__DIR__ . '/../pengaturan/pengaturan_koneksi.php');

$id = $_GET['id'];

$query  = "DELETE FROM users WHERE id='$id'";
$result = mysqli_query($koneksi, $query);

if ($result) {
    $_SESSION['toast'] = [
        'type'    => 'success',
        'message' => 'Pengguna berhasil dihapus!'
    ];
    header("Location: ../pengaturan.php");
    exit();
} else {
    die("Query Error: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
}
