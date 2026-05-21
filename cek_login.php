<?php
session_start();

// CEK LOGIN
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// KONEKSI DATABASE
$koneksi = mysqli_connect("localhost", "root", "", "db_restoran");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// AMBIL DATA USER
$query = mysqli_query($koneksi, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengaturan</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
        }
        h2 {
            text-align: center;
        }
        table {
            margin: auto;
            border-collapse: collapse;
            width: 70%;
            background: white;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background: #333;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Daftar User</h2>

<table border="1">
    <tr>
        <th>No</th>
        <th>Username</th>
        <th>Role</th>
        <th>Last Login</th>
    </tr>

    <?php 
    $no = 1;
    while ($data = mysqli_fetch_assoc($query)) {
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $data['username']; ?></td>
        <td><?= $data['role']; ?></td>
        <td><?= $data['last_login'] ?? '-'; ?></td>
    </tr>
    <?php } ?>

</table>

</body>
</html>