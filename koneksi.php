<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "curd_kasirrrestourant";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if(!$koneksi){
    die("Koneksi dengan database gagal: ". mysqli_connect_error());
}

// Set charset ke UTF-8
mysqli_set_charset($koneksi, "utf8");
?>