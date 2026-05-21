<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Kala Serasa</title>
    <meta charset="UTF-8">

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
            --border-color: #e5e7eb;
            --gradient-primary: linear-gradient(135deg, #9e5c3d 0%, #4e2818 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, .10);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, .10);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, .10);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffe4d3 0%, #dcc3b4 100%);
            color: #1f2937;
            min-height: 100vh;
        }

        .header {
            background: #fff;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 3px solid var(--primary-color);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-link {
            padding: .75rem 1.5rem;
            text-decoration: none;
            color: #6b7280;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all .2s;
        }

        .nav-link:hover {
            background: #f3f4f6;
            color: var(--primary-color);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 20px;
        }

        .search-box {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 10px;
        }

        .search-wrap {
            flex: 1;
            position: relative;
        }

        .search-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }

        .search-wrap input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: #fff;
            transition: border-color .2s;
        }

        .search-wrap input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(95, 45, 34, .10);
        }

        .order-bar {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 14px;
        }

        .input-pelanggan {
            padding: 12px 14px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            width: 250px;
            background: #fff;
            transition: border-color .2s;
        }

        .input-pelanggan:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .input-pelanggan.error {
            border-color: #ef4444;
        }

        .btn-pesan {
            padding: 0 22px;
            height: 48px;
            background: var(--gradient-primary);
            border-radius: 12px;
            color: #fff;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            transition: opacity .2s, box-shadow .2s;
        }

        .btn-pesan:hover {
            opacity: .9;
            box-shadow: var(--shadow-lg);
        }

        .keranjang-mini {
            display: none;
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            padding: 12px 16px;
            margin-bottom: 14px;
            border-left: 4px solid var(--primary-color);
        }

        .km-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .km-badge {
            background: #fce7de;
            color: #7a3a28;
            padding: 2px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .km-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            padding: 5px 0;
            border-bottom: 1px dashed #f3f4f6;
        }

        .km-item:last-child {
            border-bottom: none;
        }

        .km-nama {
            flex: 1;
        }

        .km-qty {
            color: #6b7280;
            margin: 0 10px;
            font-size: 12px;
        }

        .km-harga {
            font-weight: 600;
            color: var(--primary-color);
        }

        .km-hapus {
            background: none;
            border: none;
            cursor: pointer;
            color: #ef4444;
            font-size: 13px;
            margin-left: 8px;
        }

        .km-total {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 700;
            color: var(--primary-color);
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #f3f4f6;
        }

        .filter-bar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            background: #fff;
            padding: 12px 18px;
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            margin-bottom: 18px;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-color);
            margin-right: 4px;
            white-space: nowrap;
        }

        .filter-divider {
            width: 1px;
            height: 26px;
            background: var(--border-color);
            margin: 0 6px;
        }

        .f-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 18px;
            border-radius: 999px;
            border: 2px solid #d6b0a0;
            background: #fff;
            color: #7a4a38;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            cursor: pointer;
            transition: all .18s ease;
        }

        .f-btn:hover {
            background: #fdf0ec;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .f-btn.aktif {
            background: var(--gradient-primary);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 3px 10px rgba(95, 45, 34, .28);
        }

        .hasil-info {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 16px;
        }

        .hasil-info b {
            color: var(--primary-color);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        @media (max-width: 1100px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 780px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .order-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .input-pelanggan {
                width: 100%;
            }
        }

        .best-seller-section {
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            padding: 20px;
            margin: 18px 0 22px;
        }

        .best-seller-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .best-seller-title h2 {
            font-size: 22px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .best-seller-title p {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        .best-seller-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .best-card {
            border: 1px solid var(--border-color);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            box-shadow: var(--shadow-md);
            transition: transform .2s, box-shadow .2s;
        }

        .best-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .best-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            background: #f3f4f6;
        }

        .best-card-body {
            padding: 12px;
        }

        .best-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fef3c7;
            color: #92400e;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .best-card h3 {
            font-size: 14px;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .best-price {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 4px;
        }

        .best-sold {
            font-size: 12px;
            color: #6b7280;
        }

        @media (max-width: 1100px) {
            .best-seller-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 780px) {
            .best-seller-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: transform .2s, box-shadow .2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .card.habis {
            opacity: .62;
        }

        .card-header {
            background: var(--gradient-primary);
            color: #fff;
            padding: 10px 14px;
            font-weight: 600;
            font-size: 14px;
            line-height: 1.3;
        }

        .card-body {
            padding: 12px;
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            background: #f3f4f6;
        }

        .info-harga {
            font-weight: 700;
            font-size: 15px;
            color: var(--primary-color);
            margin: 4px 0;
        }

        .row {
            margin: 4px 0;
            font-size: 13px;
        }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-tersedia {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-habis {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-kat {
            display: inline-block;
            padding: 2px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            background: #fce7de;
            color: #7a3a28;
        }

        .stok-ok {
            color: #059669;
            font-weight: 600;
        }

        .stok-warning {
            color: #d97706;
            font-weight: 600;
        }

        .stok-habis {
            color: #ef4444;
            font-weight: 600;
        }

        .qty-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .qty-row input {
            width: 75px;
            padding: 4px 8px;
            border: 1.5px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .qty-row input:disabled {
            background: #f3f4f6;
            cursor: not-allowed;
        }

        .btn-tambah {
            margin-top: 10px;
            width: 100%;
            padding: 8px;
            background: var(--gradient-primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: opacity .2s;
        }

        .btn-tambah:hover {
            opacity: .85;
        }

        .btn-tambah:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 44px;
            margin-bottom: 12px;
            display: block;
        }

        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #1f2937;
            color: #fff;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 999;
            max-width: 320px;
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="header-content">
            <img src="assets/logo1.png" alt="Kala Serasa" style="height: 70px; width: 235px;">
            <nav>
                <a href="/Kala_Serasa/proses/proses_logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>
    </header>

    <div class="container">

        <div class="search-box">
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari produk..." oninput="jalankanFilter()">
            </div>
        </div>

        <div class="order-bar">
            <input type="text" id="namaPelanggan" class="input-pelanggan" placeholder="Nama Pelanggan *">
            <button type="button" class="btn-pesan" onclick="prosesBayar()">
                <i class="fas fa-cash-register"></i> Pesan & Bayar
            </button>
        </div>

        <div class="keranjang-mini" id="keranjangMini">
            <div class="km-title">
                <span><i class="fas fa-shopping-basket"></i>&nbsp; Pesanan</span>
                <span class="km-badge" id="kmBadge">0 item</span>
            </div>
            <div id="kmList"></div>
            <div class="km-total">
                <span>Total</span>
                <span id="kmTotal">Rp0</span>
            </div>
        </div>
        <!-- BEST SELLER -->
        <section class="best-seller-section">
            <div class="best-seller-header">
                <div class="best-seller-title">
                    <h2><i class="fas fa-fire"></i> Best Seller</h2>
                    <p>Menu yang paling banyak terjual</p>
                </div>
            </div>

            <div class="best-seller-grid">
                <?php
                $queryBestSeller = mysqli_query($koneksi, "
            SELECT 
                menu.id,
                menu.nama_menu,
                menu.harga_menu,
                menu.gambar_menu,
                menu.status,
                menu.stok,
                SUM(detail_transaksi.qty) AS total_terjual
            FROM detail_transaksi
            JOIN menu 
                ON LOWER(TRIM(detail_transaksi.nama_menu)) = LOWER(TRIM(menu.nama_menu))
            GROUP BY 
                menu.id,
                menu.nama_menu,
                menu.harga_menu,
                menu.gambar_menu,
                menu.status,
                menu.stok
            ORDER BY total_terjual DESC
            LIMIT 4
        ");

                if (!$queryBestSeller) {
                    echo "<div style='grid-column: 1 / -1; color:red; font-size:13px;'>";
                    echo "Query Best Seller Error: " . mysqli_error($koneksi);
                    echo "</div>";
                } else if (mysqli_num_rows($queryBestSeller) > 0) {
                    while ($best = mysqli_fetch_assoc($queryBestSeller)) {
                        $gambarBest = !empty($best['gambar_menu']) ? $best['gambar_menu'] : 'no-image.jpg';
                        $stokBest = (int) $best['stok'];
                        $statusBest = strtolower(trim($best['status']));
                        $habisBest = ($statusBest === 'habis' || $stokBest <= 0);
                ?>
                        <div class="best-card">
                            <img
                                src="assets/<?= htmlspecialchars($gambarBest); ?>"
                                alt="<?= htmlspecialchars($best['nama_menu']); ?>"
                                onerror="this.src='assets/no-image.jpg'">

                            <div class="best-card-body">
                                <span class="best-badge">
                                    <i class="fas fa-crown"></i> Best Seller
                                </span>

                                <h3><?= htmlspecialchars($best['nama_menu']); ?></h3>

                                <div class="best-price">
                                    Rp<?= number_format($best['harga_menu'], 0, ',', '.'); ?>
                                </div>

                                <div class="best-sold">
                                    Terjual <?= (int) $best['total_terjual']; ?>x
                                </div>

                                <div class="row">
                                    <span class="badge <?= $habisBest ? 'badge-habis' : 'badge-tersedia'; ?>">
                                        <?= $habisBest ? 'Habis' : 'Tersedia'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div style="grid-column: 1 / -1; color:#9ca3af; font-size:13px;">
                        Belum ada data best seller karena belum ada transaksi.
                    </div>
                <?php
                }
                ?>
            </div>
        </section>
        <!-- END BEST SELLER -->

        <div class="filter-bar">

            <div class="filter-bar">
                <span class="filter-label"><i class="fas fa-filter"></i>&nbsp; Kategori :</span>
                <button type="button" class="f-btn aktif" data-kat="semua"><i class="fas fa-border-all"></i> Semua</button>
                <div class="filter-divider"></div>
                <button type="button" class="f-btn" data-kat="makanan"><i class="fas fa-utensils"></i> Makanan</button>
                <button type="button" class="f-btn" data-kat="minuman"><i class="fas fa-glass-water"></i> Minuman</button>
            </div>

            <div class="hasil-info">Menampilkan <b id="jumlahHasil">0</b> menu</div>


            <div class="grid" id="gridMenu">

                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY Kategori, nama_menu");

                if ($query && mysqli_num_rows($query) > 0):
                    while ($row = mysqli_fetch_assoc($query)):
                        $id_menu   = (int) $row['id'];
                        $nama_menu = $row['nama_menu'];
                        $harga     = (int) $row['harga_menu'];

                        $kat_asli  = trim($row['Kategori']);
                        $kat_lower = strtolower($kat_asli);

                        $status = strtolower(trim($row['status']));
                        $stok   = isset($row['stok']) ? (int) $row['stok'] : 0;

                        $habis = ($status === 'habis' || $stok <= 0);

                        $gambar = !empty($row['gambar_menu']) ? $row['gambar_menu'] : 'no-image.jpg';
                        $deskripsi = trim($row['deskripsi']);
                ?>

                        <div class="card product-card <?= $habis ? 'habis' : '' ?>"
                            data-kat="<?= htmlspecialchars($kat_lower) ?>"
                            data-nama="<?= htmlspecialchars(strtolower($nama_menu)) ?>">

                            <div class="card-header"><?= htmlspecialchars($nama_menu) ?></div>

                            <div class="card-body">
                                <img src="assets/<?= htmlspecialchars($gambar) ?>"
                                    alt="<?= htmlspecialchars($nama_menu) ?>"
                                    onerror="this.src='assets/no-image.jpg'">

                                <div class="info-harga">
                                    Rp<?= number_format($harga, 0, ',', '.') ?>
                                </div>

                                <div class="row">
                                    <span class="badge-kat"><?= htmlspecialchars($kat_asli) ?></span>
                                    &nbsp;
                                    <span class="badge <?= $habis ? 'badge-habis' : 'badge-tersedia' ?>">
                                        <?= $habis ? 'Habis' : 'Tersedia' ?>
                                    </span>
                                </div>

                                <div class="row">
                                    <?php if ($stok > 10): ?>
                                        <span class="stok-ok">
                                            Stok: <?= $stok ?> porsi
                                        </span>
                                    <?php elseif ($stok > 0): ?>
                                        <span class="stok-warning">
                                            Stok: <?= $stok ?> porsi tersisa
                                        </span>
                                    <?php else: ?>
                                        <span class="stok-habis">
                                            Stok habis
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="row" style="color:#6b7280;font-size:12px;line-height:1.5">
                                    <?= htmlspecialchars(mb_strimwidth($deskripsi, 0, 75, '…')) ?>
                                </div>

                                <div class="qty-row">
                                    <label style="font-size:13px">Qty :</label>
                                    <input type="number"
                                        min="1"
                                        max="<?= $stok ?>"
                                        value="1"
                                        id="qty_<?= $id_menu ?>"
                                        <?= $habis ? 'disabled title="Menu habis"' : '' ?>>
                                </div>

                                <button type="button"
                                    class="btn-tambah"
                                    <?= $habis ? 'disabled' : '' ?>
                                    onclick='tambah(
                                    <?= $id_menu ?>,
                                    <?= json_encode($nama_menu) ?>,
                                    <?= $harga ?>,
                                    "qty_<?= $id_menu ?>",
                                    <?= $stok ?>
                                )'>
                                    <?= $habis ? '<i class="fas fa-ban"></i> Habis' : '<i class="fas fa-plus"></i> Tambah' ?>
                                </button>
                            </div>
                        </div>

                    <?php
                    endwhile;
                else:
                    ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada menu tersedia.</p>
                    </div>
                <?php endif; ?>

            </div>

        </div>

        <form id="formBayar" action="proses/proses_order.php" method="POST" style="display:none">
            <input type="hidden" name="pelanggan" id="f_pelanggan">
            <input type="hidden" name="items_json" id="f_items">
            <input type="hidden" name="total" id="f_total">
        </form>

        <div class="toast" id="toast"></div>

        <script>
            let keranjang = [];

            function tambah(id, nama, harga, qtyId, stok) {
                const qtyInput = document.getElementById(qtyId);
                let qty = parseInt(qtyInput.value);

                if (isNaN(qty) || qty <= 0) {
                    toast('Qty tidak valid!', '#ef4444');
                    qtyInput.value = 1;
                    return;
                }

                if (qty > stok) {
                    toast('Stok tidak cukup. Stok tersedia hanya ' + stok + ' porsi.', '#ef4444');
                    qtyInput.value = stok;
                    return;
                }

                const idx = keranjang.findIndex(item => item.id === id);

                if (idx > -1) {
                    const totalQty = keranjang[idx].qty + qty;

                    if (totalQty > stok) {
                        toast('Pesanan melebihi stok. Maksimal ' + stok + ' porsi.', '#ef4444');
                        return;
                    }

                    keranjang[idx].qty = totalQty;
                } else {
                    keranjang.push({
                        id: id,
                        nama: nama,
                        harga: harga,
                        qty: qty,
                        stok: stok
                    });
                }

                renderKeranjang();
                toast(nama + ' ditambahkan!');
            }

            function hapus(id) {
                keranjang = keranjang.filter(item => item.id !== id);
                renderKeranjang();
            }

            function renderKeranjang() {
                const box = document.getElementById('keranjangMini');

                if (keranjang.length === 0) {
                    box.style.display = 'none';
                    document.getElementById('kmList').innerHTML = '';
                    document.getElementById('kmTotal').textContent = 'Rp0';
                    document.getElementById('kmBadge').textContent = '0 item';
                    return;
                }

                box.style.display = 'block';

                let html = '';
                let total = 0;
                let jumlahItem = 0;

                keranjang.forEach(item => {
                    const sub = item.harga * item.qty;
                    total += sub;
                    jumlahItem += item.qty;

                    html += `
                    <div class="km-item">
                        <span class="km-nama">${escapeHtml(item.nama)}</span>
                        <span class="km-qty">${item.qty}x</span>
                        <span class="km-harga">Rp${rp(sub)}</span>
                        <button type="button" class="km-hapus" onclick="hapus(${item.id})" title="Hapus">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                });

                document.getElementById('kmList').innerHTML = html;
                document.getElementById('kmTotal').textContent = 'Rp' + rp(total);
                document.getElementById('kmBadge').textContent = jumlahItem + ' item';
            }

            function prosesBayar() {
                const pelEl = document.getElementById('namaPelanggan');
                const pelanggan = pelEl.value.trim();

                if (!pelanggan) {
                    pelEl.classList.add('error');
                    pelEl.focus();
                    toast('Isi nama pelanggan dulu!', '#ef4444');
                    return;
                }

                pelEl.classList.remove('error');

                if (keranjang.length === 0) {
                    toast('Tambah menu dulu ke pesanan!', '#ef4444');
                    return;
                }

                for (const item of keranjang) {
                    if (item.qty <= 0) {
                        toast('Qty pesanan tidak valid.', '#ef4444');
                        return;
                    }

                    if (item.qty > item.stok) {
                        toast('Pesanan ' + item.nama + ' melebihi stok.', '#ef4444');
                        return;
                    }
                }

                const total = keranjang.reduce((sum, item) => sum + item.harga * item.qty, 0);

                document.getElementById('f_pelanggan').value = pelanggan;
                document.getElementById('f_items').value = JSON.stringify(keranjang);
                document.getElementById('f_total').value = total;

                document.getElementById('formBayar').submit();
            }

            const semuaKartu = Array.from(document.querySelectorAll('.product-card'));

            document.querySelectorAll('.f-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.f-btn').forEach(b => b.classList.remove('aktif'));
                    this.classList.add('aktif');
                    jalankanFilter();
                });
            });

            function jalankanFilter() {
                const tombolAktif = document.querySelector('.f-btn.aktif');
                const filterAktif = tombolAktif ? tombolAktif.dataset.kat : 'semua';
                const cari = document.getElementById('searchInput').value.toLowerCase().trim();

                let jumlah = 0;

                semuaKartu.forEach(kartu => {
                    const cocokKat = filterAktif === 'semua' || kartu.dataset.kat === filterAktif;
                    const cocokCari = cari === '' ||
                        kartu.dataset.nama.includes(cari) ||
                        kartu.innerText.toLowerCase().includes(cari);

                    if (cocokKat && cocokCari) {
                        kartu.style.display = 'block';
                        jumlah++;
                    } else {
                        kartu.style.display = 'none';
                    }
                });

                document.getElementById('jumlahHasil').textContent = jumlah;

                const emptyState = document.getElementById('emptyState');
                if (emptyState) {
                    emptyState.remove();
                }

                if (jumlah === 0 && semuaKartu.length > 0) {
                    document.getElementById('gridMenu').insertAdjacentHTML(
                        'beforeend',
                        '<div class="empty-state" id="emptyState"><i class="fas fa-search"></i><p>Tidak ada menu yang ditemukan.</p></div>'
                    );
                }
            }

            function rp(n) {
                return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function toast(msg, bg = '#1f2937') {
                const t = document.getElementById('toast');
                t.textContent = msg;
                t.style.background = bg;
                t.style.display = 'block';

                clearTimeout(window._toastTimer);
                window._toastTimer = setTimeout(() => {
                    t.style.display = 'none';
                }, 2500);
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            window.addEventListener('DOMContentLoaded', jalankanFilter);
        </script>

</body>

</html>