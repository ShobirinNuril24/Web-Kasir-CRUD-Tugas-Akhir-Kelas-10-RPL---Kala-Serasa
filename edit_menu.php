<?php
include('koneksi.php');
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM menu WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die("Query Error: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    }

    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan');window.location='index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Masukkan ID');window.location='index.php';</script>";
    exit;
}

if (isset($_GET['error'])) {
    echo "<script>alert('Error: " . htmlspecialchars($_GET['error']) . "');</script>";
}

$stok = isset($data['stok']) ? (int) $data['stok'] : 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style type="text/css">
        * {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary-color: #5f2d22;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --border-color: #e5e7eb;
            --gradient-primary: linear-gradient(135deg, #9e5c3d 0%, #4e2818 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        body {
            background: var(--gradient-primary);
        }

        h1 {
            font-size: 3rem;
            text-transform: uppercase;
            color: #fff;
        }

        .base {
            width: 500px;
            padding: 70px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 10px;
            background-color: #ededed;
        }

        label {
            margin-top: 10px;
            float: left;
            text-align: left;
            width: 100%;
        }

        input {
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            background-color: #f8f8f8;
            border: 2px solid #ccc;
            outline-color: var(--primary-color);
            border-radius: 8px;
        }

        input:hover {
            transform: translateY(2px);
            box-shadow: var(--shadow-lg);
            transition: all 0.2s ease;
        }

        input[readonly] {
            background-color: #e5e7eb;
            color: #374151;
            cursor: not-allowed;
        }

        input[readonly]:hover {
            transform: none;
            box-shadow: none;
        }

        select {
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            background-color: #f8f8f8;
            border: 2px solid #ccc;
            outline-color: var(--primary-color);
            border-radius: 8px;
        }

        button {
            background: var(--gradient-primary);
            color: #fff;
            padding: 10px;
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

        .info-stok {
            float: left;
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
            margin-bottom: 8px;
        }

        .stok-hijau {
            color: #059669;
            font-weight: 600;
        }

        .stok-kuning {
            color: #d97706;
            font-weight: 600;
        }

        .stok-merah {
            color: #ef4444;
            font-weight: 600;
        }

        .btn-stok {
            display: inline-block;
            margin-top: 8px;
            float: left;
            font-size: 12px;
            text-decoration: none;
            color: #fff;
            background: var(--gradient-success);
            padding: 7px 10px;
            border-radius: 8px;
            box-shadow: var(--shadow-md);
        }

        .btn-stok:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <center>
        <h1>Edit Menu <?= htmlspecialchars($data['nama_menu']); ?></h1>
    </center>

    <form method="POST" action="proses/proses_edit.php" enctype="multipart/form-data">
        <section class="base">

            <div>
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" autofocus required value="<?= htmlspecialchars($data['nama_menu']); ?>" />
                <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']); ?>" />
            </div>

            <div>
                <label>Deskripsi</label>
                <input type="text" name="deskripsi" value="<?= htmlspecialchars($data['deskripsi']); ?>" />
            </div>

            <div>
                <label>Harga Menu</label>
                <input type="number" name="harga_menu" value="<?= htmlspecialchars($data['harga_menu']); ?>" required />
            </div>

            <div>
                <label>Stok Saat Ini</label>
                <input type="number" value="<?= $stok; ?>" readonly />

                <small class="info-stok">
                    <?php if ($stok > 10): ?>
                        <span class="stok-hijau">Stok aman: <?= $stok; ?> porsi.</span>
                    <?php elseif ($stok > 0): ?>
                        <span class="stok-kuning">Stok mulai sedikit: <?= $stok; ?> porsi.</span>
                    <?php else: ?>
                        <span class="stok-merah">Stok habis.</span>
                    <?php endif; ?>
                    Stok hanya bisa ditambah melalui halaman Tambah Stok.
                </small>

                <a href="tambah_stok.php?id=<?= htmlspecialchars($data['id']); ?>" class="btn-stok">
                    Tambah Stok
                </a>
            </div>

            <div style="clear: both;">
                <label>Gambar Produk</label>

                <?php if (!empty($data['gambar_menu']) && file_exists('assets/' . $data['gambar_menu'])): ?>
                    <img src="assets/<?= htmlspecialchars($data['gambar_menu']); ?>" style="width:120px; float:left; margin-bottom: 5px;">
                <?php else: ?>
                    <div style="width:120px; height:90px; float:left; margin-bottom:5px; background:#ddd; display:flex; align-items:center; justify-content:center; font-size:12px; color:#777;">
                        Tidak ada gambar
                    </div>
                <?php endif; ?>

                <input type="file" name="gambar_menu" />
                <i style="float: left; font-size: 11px; color: red;">Abaikan jika tidak merubah gambar produk</i>
            </div>

            <div style="clear: both;">
                <label>Status</label>
                <select name="status" required>
                    <option value="tersedia" <?php if ($data['status'] == 'tersedia') echo 'selected'; ?>>Tersedia</option>
                    <option value="habis" <?php if ($data['status'] == 'habis') echo 'selected'; ?>>Habis</option>
                </select>
            </div>

            <div>
                <button type="submit" name="simpan">Simpan Produk</button>
                <button style="margin-left: 10px;" type="button" onclick="history.back()" class="btn btn-secondary">Kembali</button>
            </div>

        </section>
    </form>
</body>

</html>