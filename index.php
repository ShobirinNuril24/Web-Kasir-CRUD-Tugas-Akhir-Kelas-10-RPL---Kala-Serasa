<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header('Location: user_home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kala Serasa</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffe4d3 0%, #dcc3b4 100%);
            color: var(--dark-color);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header {
            background: #fff;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 3px solid var(--primary-color);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .nav-menu {
            display: flex;
            gap: 1rem;
        }

        .nav-link {
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            color: #6b7280;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            background: #f3f4f6;
            color: var(--primary-color);
        }

        .nav-link.active {
            background: var(--gradient-primary);
            color: #fff;
        }

        .main-content {
            padding: 2rem 0 4rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 0.5rem;
        }

        .page-title i {
            color: var(--primary-color);
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .btn-tambah {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gradient-primary);
            color: #fff;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
        }

        .btn-tambah:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: var(--gradient-primary);
        }

        .stat-icon.green {
            background: var(--gradient-success);
        }

        .stat-icon.red {
            background: var(--gradient-danger);
        }

        .stat-icon.amber {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .stat-info h3 {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .stat-info p {
            color: #6b7280;
            font-size: 0.85rem;
        }

        .table-controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .search-box {
            flex: 1;
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.9rem;
            background: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .filter-box select {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.9rem;
            background: #fff;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        .table-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow-x: auto;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 860px;
        }

        .product-table thead {
            background: var(--primary-color);
        }

        .product-table th {
            padding: 0.9rem 0.75rem;
            text-align: left;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .product-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s ease;
        }

        .product-table tbody tr:hover {
            background: #f9fafb;
        }

        .product-table td {
            padding: 0.6rem 0.75rem;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            width: 100%;
            height: 100%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 1.1rem;
        }

        .product-desc {
            color: #6b7280;
            font-size: 0.82rem;
        }

        .price {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--success-color);
            white-space: nowrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.3rem 0.65rem;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .stok-ok {
            font-weight: 600;
            color: #059669;
            font-size: 0.83rem;
        }

        .stok-warning {
            font-weight: 600;
            color: #d97706;
            font-size: 0.83rem;
        }

        .stok-habis {
            font-weight: 600;
            color: #ef4444;
            font-size: 0.83rem;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.78rem;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-stok {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-edit:hover {
            background: #3b82f6;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-stok:hover {
            background: #f59e0b;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-delete:hover {
            background: #ef4444;
            color: #fff;
            transform: translateY(-1px);
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            z-index: 9999;
            font-size: 14px;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .footer {
            background: #fff;
            border-top: 1px solid var(--border-color);
            padding: 2rem 0;
            margin-top: 4rem;
        }

        .footer p {
            text-align: center;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .table-controls {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <img src="assets/logo1.png" alt="logo1" style="height:70px; width:235px;">

                <nav class="nav-menu">
                    <a href="index.php" class="nav-link active">
                        <i class="fas fa-box"></i> Produk
                    </a>
                    <a href="laporan.php" class="nav-link">
                        <i class="fas fa-chart-line"></i> Laporan
                    </a>
                    <a href="pengaturan.php" class="nav-link">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                    <a href="proses/proses_logout.php" class="nav-link">
                        <i class="fas fa-sign-out"></i> Logout
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">

            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="fas fa-boxes"></i> Kelola Produk
                    </h2>
                    <p class="page-subtitle">Atur dan kelola semua produk Anda dengan mudah</p>
                </div>

                <a href="tambah_produk.php" class="btn-tambah">
                    <i class="fas fa-plus-circle"></i> Tambah Produk Baru
                </a>
            </div>

            <div class="stats-grid">
                <?php
                $total_produk_q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM menu");
                $total_produk_d = mysqli_fetch_assoc($total_produk_q);
                $total_produk = $total_produk_d['total'] ?? 0;

                $produk_tersedia_q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM menu WHERE status='tersedia'");
                $produk_tersedia_d = mysqli_fetch_assoc($produk_tersedia_q);
                $produk_tersedia = $produk_tersedia_d['total'] ?? 0;

                $produk_habis_q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM menu WHERE status='habis'");
                $produk_habis_d = mysqli_fetch_assoc($produk_habis_q);
                $produk_habis = $produk_habis_d['total'] ?? 0;

                $stok_q = mysqli_query($koneksi, "SELECT SUM(stok) AS total FROM menu");
                $stok_d = mysqli_fetch_assoc($stok_q);
                $total_stok = $stok_d['total'] ?? 0;
                ?>

                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $total_produk; ?></h3>
                        <p>Total Produk</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $produk_tersedia; ?></h3>
                        <p>Tersedia</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon red">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $produk_habis; ?></h3>
                        <p>Stok Habis</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon amber">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $total_stok; ?></h3>
                        <p>Total Stok</p>
                    </div>
                </div>
            </div>

            <div class="table-controls">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="filterAndSearchTable()">
                </div>

                <div class="filter-box">
                    <select id="statusFilter" onchange="filterAndSearchTable()">
                        <option value="">Semua Status</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>
            </div>

            <div class="table-container">
                <table class="product-table" id="productTable">
                    <thead>
                        <tr>
                            <th style="width:4%">No</th>
                            <th style="width:22%">Produk</th>
                            <th style="width:24%">Deskripsi</th>
                            <th style="width:13%">Harga</th>
                            <th style="width:11%">Status</th>
                            <th style="width:9%">Stok</th>
                            <th style="width:17%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $query = "SELECT * FROM menu ORDER BY status = 'tersedia' DESC, id ASC";
                        $result = mysqli_query($koneksi, $query);

                        if (!$result) {
                            die("Query Error: " . mysqli_error($koneksi));
                        }

                        if (mysqli_num_rows($result) > 0):
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)):
                                $id_menu = (int) $row['id'];
                                $nama_menu = $row['nama_menu'];
                                $deskripsi = $row['deskripsi'];
                                $harga_menu = (int) $row['harga_menu'];
                                $status = strtolower(trim($row['status']));
                                $stok = isset($row['stok']) ? (int) $row['stok'] : 0;
                                $gambar_menu = $row['gambar_menu'];
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <div class="product-info">
                                            <div class="product-image">
                                                <?php if (!empty($gambar_menu) && file_exists('assets/' . $gambar_menu)): ?>
                                                    <img src="assets/<?= htmlspecialchars($gambar_menu); ?>" alt="<?= htmlspecialchars($nama_menu); ?>">
                                                <?php else: ?>
                                                    <div class="no-image">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <strong><?= htmlspecialchars($nama_menu); ?></strong>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="product-desc">
                                            <?= htmlspecialchars($deskripsi); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="price">
                                            Rp <?= number_format($harga_menu, 0, ',', '.'); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($status == 'tersedia'): ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Tersedia
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times"></i> Habis
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($stok > 10): ?>
                                            <span class="stok-ok"><?= $stok; ?> porsi</span>
                                        <?php elseif ($stok > 0): ?>
                                            <span class="stok-warning"><?= $stok; ?> ⚠️</span>
                                        <?php else: ?>
                                            <span class="stok-habis">Habis</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit_menu.php?id=<?= $id_menu; ?>"
                                                class="btn-action btn-edit"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="tambah_stok.php?id=<?= $id_menu; ?>"
                                                class="btn-action btn-stok"
                                                title="Tambah Stok">
                                                <i class="fas fa-plus"></i>
                                            </a>

                                            <a href="hapus_produk.php?id=<?= $id_menu; ?>"
                                                class="btn-action btn-delete"
                                                onclick="return confirm('Yakin hapus <?= htmlspecialchars(addslashes($nama_menu)); ?>?')"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <tr>
                                <td colspan="7" style="text-align:center; padding:3rem; color:#9ca3af;">
                                    <i class="fas fa-inbox" style="font-size:3rem; opacity:0.5; display:block; margin-bottom:1rem;"></i>
                                    Belum ada produk. Tambahkan produk pertama Anda!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>
                &copy; 2026 My Market. Dibuat dengan
                <i class="fas fa-heart" style="color:#ef4444;"></i>
                oleh Kala Serasa Group
            </p>
        </div>
    </footer>

    <div id="toast" class="toast"></div>

    <?php if (isset($_SESSION['toast'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toast = document.getElementById('toast');

                const data = {
                    type: <?= json_encode($_SESSION['toast']['type']); ?>,
                    message: <?= json_encode($_SESSION['toast']['message']); ?>
                };

                toast.innerText = data.message;
                toast.style.background = data.type === 'success' ? '#10b981' : '#ef4444';
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            });
        </script>

        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

    <script>
        function filterAndSearchTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const statusValue = document.getElementById('statusFilter').value.toLowerCase();

            const rows = document.querySelectorAll('#productTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const statusCell = row.querySelectorAll('td')[4];

                if (!statusCell) {
                    return;
                }

                const statusText = statusCell.textContent.toLowerCase();

                const cocokSearch = text.includes(searchValue);
                const cocokStatus = statusValue === '' || statusText.includes(statusValue);

                row.style.display = cocokSearch && cocokStatus ? '' : 'none';
            });
        }
    </script>

</body>

</html>