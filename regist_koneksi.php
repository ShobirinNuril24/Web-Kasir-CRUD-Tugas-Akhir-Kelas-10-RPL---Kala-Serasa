<?php
$conn = mysqli_connect("localhost","root","","curd_kasirrrestourant");

if(!$conn){
  die("Koneksi gagal: " . mysqli_connect_error());
}
?>