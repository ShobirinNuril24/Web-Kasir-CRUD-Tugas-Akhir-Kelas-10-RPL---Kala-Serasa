<?php
include('koneksi.php');

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'hari';
$tanggal_dari   = isset($_GET['dari'])   ? $_GET['dari']   : date('Y-m-d');
$tanggal_sampai = isset($_GET['sampai']) ? $_GET['sampai'] : date('Y-m-d');

if ($filter == 'hari') {
    $where = "WHERE DATE(tanggal) = CURDATE()";
    $label = "Hari Ini (" . date('d-m-Y') . ")";
} elseif ($filter == 'minggu') {
    $where = "WHERE YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)";
    $label = "Minggu Ini";
} elseif ($filter == 'bulan') {
    $where = "WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())";
    $label = "Bulan Ini (" . date('F Y') . ")";
} elseif ($filter == 'custom') {
    $where = "WHERE DATE(tanggal) BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
    $label = "$tanggal_dari s/d $tanggal_sampai";
} else {
    $where = "WHERE DATE(tanggal) = CURDATE()";
    $label = "Hari Ini";
}

$q = mysqli_query($koneksi, "SELECT SUM(total_harga) as total, COUNT(*) as jumlah FROM transaksi $where");
$d = mysqli_fetch_array($q);
$total_uang      = $d['total'] ?? 0;
$total_transaksi = $d['jumlah'] ?? 0;

$q_list = mysqli_query($koneksi, "SELECT * FROM transaksi $where ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan - Kala Serasa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #5f2d22;
            --gradient: linear-gradient(135deg, #9e5c3d 0%, #4e2818 100%);
            --bg: linear-gradient(135deg, #ffe4d3 0%, #dcc3b4 100%);
            --border: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            min-height: 100vh;
        }

        .header {
            background: #fff;
            box-shadow: var(--shadow);
            border-bottom: 3px solid var(--primary);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-link {
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            color: #6b7280;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background: #f3f4f6;
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--gradient);
            color: #fff;
        }

        .nav-menu {
            display: flex;
            gap: 0.5rem;
        }

        .main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 20px;
        }

        h1 {
            color: var(--primary);
            font-size: 1.6rem;
            margin-bottom: 1.5rem;
        }

        .filter-bar {
            background: #fff;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        .filter-btn {
            padding: 0.4rem 1rem;
            border-radius: 8px;
            border: 2px solid var(--border);
            background: #f9fafb;
            color: #374151;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--gradient);
            color: #fff;
            border-color: var(--primary);
        }

        .filter-custom {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .filter-custom input[type="date"] {
            padding: 0.4rem 0.75rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            outline: none;
        }

        .btn-cari {
            padding: 0.4rem 1rem;
            background: var(--gradient);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            cursor: pointer;
        }

        .ringkasan {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .box {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .box-label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .box-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary);
        }

        .box-periode {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 0.25rem;
        }

        .tabel-wrap {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .tabel-head {
            background: var(--gradient);
            padding: 1rem 1.5rem;
        }

        .tabel-head h3 {
            color: #fff;
            font-size: 0.95rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 0.75rem 1rem;
            background: #f9fafb;
            text-align: left;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 2px solid var(--border);
        }

        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.88rem;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fafafa;
        }

        /* Tombol cetak ulang */
        .btn-cetak-ulang {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.35rem 0.85rem;
            background: var(--gradient);
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: opacity 0.2s;
        }

        .btn-cetak-ulang:hover {
            opacity: 0.85;
        }

        .kosong {
            text-align: center;
            padding: 3rem;
            color: #9ca3af;
        }

        .kosong i {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
            opacity: 0.4;
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 2rem;
        }

        @media print {

            .header,
            .filter-bar,
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="header-content">
            <img src="assets/logo1.png" alt="logo" style="height:60px;">
            <nav class="nav-menu">
                <a href="index.php" class="nav-link"><i class="fas fa-box"></i> Produk</a>
                <a href="laporan.php" class="nav-link active"><i class="fas fa-chart-line"></i> Laporan</a>
                <a href="pengaturan.php" class="nav-link"><i class="fas fa-cog"></i> Pengaturan</a>
                <a href="/Kala_Serasa/proses/proses_logout.php" class="nav-link"><i class="fas fa-sign-out"></i> Logout</a>
            </nav>
        </div>
    </header>

    <div class="main">

        <h1><i class="fas fa-chart-bar"></i> Laporan Penjualan</h1>

        <!-- FILTER -->
        <div class="filter-bar">
            <span style="font-weight:600; color:var(--primary); margin-right:0.25rem;">
                <i class="fas fa-filter"></i> Filter:
            </span>
            <a href="?filter=hari" class="filter-btn <?= $filter == 'hari'   ? 'active' : '' ?>">Hari Ini</a>
            <a href="?filter=minggu" class="filter-btn <?= $filter == 'minggu' ? 'active' : '' ?>">Minggu Ini</a>
            <a href="?filter=bulan" class="filter-btn <?= $filter == 'bulan'  ? 'active' : '' ?>">Bulan Ini</a>
            <form method="GET" class="filter-custom">
                <input type="hidden" name="filter" value="custom">
                <input type="date" name="dari" value="<?= $tanggal_dari ?>">
                <span style="color:#6b7280">s/d</span>
                <input type="date" name="sampai" value="<?= $tanggal_sampai ?>">
                <button type="submit" class="btn-cari"><i class="fas fa-search"></i> Cari</button>
            </form>
            <button onclick="window.print()" class="filter-btn no-print" style="margin-left:auto;">
                <i class="fas fa-print"></i> Print
            </button>
        </div>

        <!-- RINGKASAN -->
        <div class="ringkasan">
            <div class="box">
                <div class="box-label">💰 Total Uang Masuk</div>
                <div class="box-value">Rp<?= number_format($total_uang, 0, ',', '.') ?></div>
                <div class="box-periode"><?= $label ?></div>
            </div>
            <div class="box">
                <div class="box-label">🧾 Total Transaksi</div>
                <div class="box-value"><?= $total_transaksi ?> order</div>
                <div class="box-periode"><?= $label ?></div>
            </div>
        </div>

        <!-- TABEL TRANSAKSI -->
        <div class="tabel-wrap">
            <div class="tabel-head">
                <h3><i class="fas fa-list"></i> Daftar Transaksi — <?= $label ?></h3>
            </div>

            <?php if (mysqli_num_rows($q_list) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Nota</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Uang Masuk</th>
                            <th>Tanggal</th>
                            <th class="no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($r = mysqli_fetch_array($q_list)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><b><?= htmlspecialchars($r['no_nota']) ?></b></td>
                                <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
                                <td><?= htmlspecialchars($r['kasir']) ?></td>
                                <td style="color:#059669; font-weight:700;">
                                    Rp<?= number_format($r['total_harga'], 0, ',', '.') ?>
                                </td>
                                <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                                <td class="no-print">
                                    <a href="struk.php?nota=<?= urlencode($r['no_nota']) ?>"
                                        target="_blank"
                                        class="btn-cetak-ulang">
                                        <i class="fas fa-print"></i> Cetak Struk
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="kosong">
                    <i class="fas fa-receipt"></i>
                    <p>Tidak ada transaksi pada periode ini</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <footer>&copy; 2026 Kala Serasa Group</footer>

</body>

</html>