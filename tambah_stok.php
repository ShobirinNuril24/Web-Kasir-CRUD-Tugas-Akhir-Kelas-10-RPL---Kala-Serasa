<?php
session_start();
include('koneksi.php');

// CEK LOGIN
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// CEK ROLE ADMIN
if ($_SESSION['role'] != 'admin') {
    header('Location: user_home.php');
    exit;
}

// CEK ID
if (!isset($_GET['id'])) {
    echo "<script>alert('ID menu tidak ditemukan'); window.location='index.php';</script>";
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = '$id'");

if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Menu tidak ditemukan'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Stok</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary-color: #5f2d22;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --gradient-primary: linear-gradient(135deg, #9e5c3d 0%, #4e2818 100%);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        body {
            background: var(--gradient-primary);
            min-height: 100vh;
            margin: 0;
        }

        h1 {
            font-size: 3rem;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
            margin-top: 40px;
        }

        .base {
            width: 500px;
            padding: 60px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 10px;
            background-color: #ededed;
        }

        label {
            margin-top: 12px;
            float: left;
            text-align: left;
            width: 100%;
            font-weight: 500;
        }

        input {
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            background-color: #f8f8f8;
            border: 2px solid #ccc;
            outline-color: var(--primary-color);
            border-radius: 8px;
            margin-top: 5px;
        }

        input:hover {
            transform: translateY(2px);
            box-shadow: var(--shadow-lg);
            transition: all 0.2s ease;
        }

        .info-box {
            background: #fff;
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: var(--shadow-lg);
        }

        .info-box p {
            margin: 6px 0;
            color: var(--dark-color);
        }

        .stok-sekarang {
            font-weight: 700;
            color: var(--success-color);
        }

        button {
            background: var(--gradient-primary);
            color: #fff;
            padding: 10px 16px;
            font-size: 1rem;
            border: 0;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        button:hover {
            transform: translateY(2px);
            box-shadow: var(--shadow-xl);
        }

        .btn-kembali {
            margin-left: 10px;
            background: #6b7280;
        }
    </style>
</head>

<body>

    <h1>Tambah Stok</h1>

    <form method="POST" action="proses/proses_tambah_stok.php">
        <section class="base">

            <div class="info-box">
                <p><strong>Nama Menu:</strong> <?= htmlspecialchars($data['nama_menu']); ?></p>
                <p><strong>Harga:</strong> Rp <?= number_format($data['harga_menu'], 0, ',', '.'); ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($data['status']); ?></p>
                <p><strong>Stok Sekarang:</strong>
                    <span class="stok-sekarang"><?= (int) $data['stok']; ?> porsi</span>
                </p>
            </div>

            <input type="hidden" name="id" value="<?= $data['id']; ?>">
            <input type="hidden" name="stok_lama" value="<?= (int) $data['stok']; ?>">

            <div>
                <label>Jumlah Stok yang Ditambahkan</label>
                <input type="number" name="stok_tambah" min="1" required autofocus placeholder="Contoh: 10">
            </div>

            <div>
                <button type="submit" name="simpan">Tambah Stok</button>
                <button type="button" onclick="history.back()" class="btn-kembali">Kembali</button>
            </div>

        </section>
    </form>

</body>

</html>