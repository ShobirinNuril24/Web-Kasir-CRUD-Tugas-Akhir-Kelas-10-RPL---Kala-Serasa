<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kala_serasa";

$conn = new mysqli ($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "ALTER TABLE users ADD last_login DATETIME";
if ($conn->query($sql) === TRUE) {
    echo "Column added succesfully";
} else {
    echo "Error adding column: " . $conn->error;
}

$conn->close();

mysqli_query($koneksi, "UPDATE users SET last_login = NOW() WHERE username='$username'");
?>