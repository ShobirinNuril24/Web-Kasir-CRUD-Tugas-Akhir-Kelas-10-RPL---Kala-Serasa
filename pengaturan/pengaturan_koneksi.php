<?php

$server = "localhost";
$user = "root";
$password = "";
$nama_db = "curd_kasirrrestourant";
$koneksi = mysqli_connect($server,$user,$password,$nama_db); 

if($koneksi == TRUE){
    // echo "Berhasil Terhubung ke Database";
}else{
echo "Gagal Terhubung";  
}

?>