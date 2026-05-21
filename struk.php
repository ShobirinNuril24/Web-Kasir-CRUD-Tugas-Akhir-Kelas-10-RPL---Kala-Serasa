<?php
session_start();
include('koneksi.php');

$mode_cetak_ulang = isset($_GET['nota']);

if ($mode_cetak_ulang) {
    $no_nota = mysqli_real_escape_string($koneksi, $_GET['nota']);

    // Ambil data transaksi berdasarkan no_nota
    $q = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE no_nota='$no_nota'");

    if ($q === false || mysqli_num_rows($q) == 0) {
        die("<div style='font-family:monospace;text-align:center;margin-top:80px'>
            <p>❌ Nota tidak ditemukan.</p>
            <a href='laporan.php'>← Kembali</a></div>");
    }

    $transaksi  = mysqli_fetch_array($q);
    $id_transaksi = $transaksi['id_transaksi']; // ambil id_transaksi
    $pelanggan  = $transaksi['nama_pelanggan'];
    $total      = $transaksi['total_harga'];
    $kasir      = $transaksi['kasir'];
    $no_nota    = $transaksi['no_nota'];
    $tanggal    = date('d-m-Y', strtotime($transaksi['tanggal']));

    // Ambil detail item menggunakan id_transaksi
    $q_detail = mysqli_query($koneksi, "SELECT * FROM detail_transaksi WHERE id_transaksi='$id_transaksi'");

    if ($q_detail === false) {
        die("<div style='font-family:monospace;text-align:center;margin-top:80px'>
            <p>❌ Error: " . mysqli_error($koneksi) . "</p>
            <a href='laporan.php'>← Kembali</a></div>");
    }

    $items = [];
    while ($row = mysqli_fetch_array($q_detail)) {
        $items[] = [
            'nama'  => $row['nama_menu'],
            'harga' => $row['harga_menu'],
            'qty'   => $row['qty'],
        ];
    }

    $sudah_bayar = true;
    $uang_bayar  = $total;
    $kembalian   = 0;
} else {
    if (!isset($_SESSION['struk'])) {
        header("Location: user_home.php");
        exit;
    }

    $data      = $_SESSION['struk'];
    $pelanggan = $data['pelanggan'];
    $total     = $data['total'];
    $items     = $data['items'];
    $no_nota   = $data['no_nota'];
    $kasir     = $data['kasir'];
    $tanggal   = date('d-m-Y');

    $sudah_bayar = isset($_POST['uang_bayar']);
    $uang_bayar  = $sudah_bayar ? (int) $_POST['uang_bayar'] : 0;
    $kembalian   = $sudah_bayar ? ($uang_bayar - $total) : 0;

    if ($sudah_bayar && $uang_bayar < $total) {
        $error       = "Uang yang dibayarkan kurang! Minimal Rp" . number_format($total, 0, ',', '.');
        $sudah_bayar = false;
    }

    if ($sudah_bayar && $kembalian >= 0) {
        unset($_SESSION['struk']);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: monospace;
            background: #f4f4f4;
        }

        .bayar-wrap {
            width: 320px;
            margin: 60px auto;
            background: white;
            padding: 24px 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .bayar-wrap h3 {
            text-align: center;
            margin-bottom: 4px;
            font-size: 16px;
            color: #5f2d22;
        }

        .bayar-wrap .sub {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-bottom: 16px;
        }

        .total-box {
            background: #f9f0eb;
            border: 1px dashed #9e5c3d;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            margin-bottom: 16px;
        }

        .total-box .label {
            font-size: 12px;
            color: #888;
        }

        .total-box .nilai {
            font-size: 22px;
            font-weight: bold;
            color: #5f2d22;
        }

        .bayar-wrap label {
            font-size: 13px;
            color: #444;
            display: block;
            margin-bottom: 6px;
        }

        .bayar-wrap input[type="number"] {
            width: 100%;
            padding: 10px;
            font-size: 15px;
            border: 2px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            outline: none;
            font-family: monospace;
        }

        .bayar-wrap input[type="number"]:focus {
            border-color: #9e5c3d;
        }

        .error-msg {
            background: #fee2e2;
            color: #991b1b;
            font-size: 12px;
            padding: 8px 10px;
            border-radius: 6px;
            margin-top: 8px;
            text-align: center;
        }

        .nominal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 6px;
            margin: 10px 0 14px;
        }

        .nominal-btn {
            padding: 7px 4px;
            background: #f3e8e2;
            border: 1px solid #d4a898;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            color: #5f2d22;
            font-family: monospace;
            transition: all 0.15s;
        }

        .nominal-btn:hover {
            background: #9e5c3d;
            color: white;
        }

        .kembalian-preview {
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            margin: 12px 0;
            display: none;
        }

        .kembalian-preview.cukup {
            background: #d1fae5;
            border: 1px dashed #10b981;
        }

        .kembalian-preview.kurang {
            background: #fee2e2;
            border: 1px dashed #ef4444;
        }

        .kembalian-preview .label {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
        }

        .kembalian-preview .nilai {
            font-size: 20px;
            font-weight: bold;
        }

        .kembalian-preview.cukup .nilai {
            color: #065f46;
        }

        .kembalian-preview.kurang .nilai {
            color: #991b1b;
        }

        .struk {
            width: 300px;
            margin: 30px auto;
            background: white;
            padding: 15px;
            border: 1px solid #ccc;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
            font-size: 13px;
        }

        .item-nama {
            flex: 1;
        }

        .item-qty {
            margin: 0 8px;
            color: #555;
        }

        .item-sub {
            font-weight: bold;
        }

        .kembalian-box {
            background: #d1fae5;
            border-radius: 4px;
            padding: 6px 8px;
            margin: 6px 0;
            text-align: center;
        }

        .kembalian-box span {
            font-size: 15px;
            font-weight: bold;
            color: #065f46;
        }

        .badge-ulang {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 8px;
        }

        button {
            margin-top: 10px;
            padding: 8px;
            width: 100%;
            background: #9e5c3d;
            color: white;
            border: none;
            cursor: pointer;
            font-family: monospace;
        }

        button.kembali {
            background: #5f2f02;
            margin-top: 6px;
        }

        button.bayar-btn {
            background: #9e5c3d;
            font-size: 14px;
            padding: 10px;
            margin-top: 14px;
            border-radius: 6px;
            font-family: monospace;
        }

        button.bayar-btn:hover {
            background: #5f2d22;
        }

        button.bayar-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        @media print {
            button {
                display: none;
            }

            .struk {
                border: none;
            }

            .badge-ulang {
                display: none;
            }
        }
    </style>
</head>

<body>

    <?php if (!$sudah_bayar): ?>
        <div class="bayar-wrap">
            <h3>Input Pembayaran</h3>
            <p class="sub">Masukkan uang yang diberikan pelanggan</p>
            <div class="total-box">
                <div class="label">Total yang harus dibayar</div>
                <div class="nilai">Rp<?= number_format($total, 0, ',', '.') ?></div>
            </div>
            <?php if (isset($error)): ?>
                <div class="error-msg">⚠️ <?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <label>Uang Bayar (Rp)</label>
                <input type="number" name="uang_bayar" id="uang_bayar"
                    placeholder="Contoh: 50000" min="<?= $total ?>" required autofocus
                    oninput="hitungKembalian()">
                <div class="kembalian-preview" id="kembalianPreview">
                    <div class="label" id="kembalianLabel"></div>
                    <div class="nilai" id="kembalianNilai"></div>
                </div>
                <div class="nominal-grid">
                    <?php foreach ([5000, 10000, 20000, 50000, 100000, 200000] as $n): ?>
                        <button type="button" class="nominal-btn" onclick="setNominal(<?= $n ?>)">
                            Rp<?= number_format($n, 0, ',', '.') ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="nominal-btn"
                    style="width:100%;margin-bottom:4px;font-size:12px;"
                    onclick="setNominal(<?= $total ?>)">
                    ✅ Uang Pas (Rp<?= number_format($total, 0, ',', '.') ?>)
                </button>
                <button type="submit" class="bayar-btn" id="btnBayar">Proses & Tampilkan Struk →</button>
            </form>
        </div>
        <script>
            const total = <?= $total ?>;

            function formatRp(n) {
                return 'Rp' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function setNominal(n) {
                document.getElementById('uang_bayar').value = n;
                hitungKembalian();
            }

            function hitungKembalian() {
                const input = document.getElementById('uang_bayar').value;
                const preview = document.getElementById('kembalianPreview');
                const label = document.getElementById('kembalianLabel');
                const nilai = document.getElementById('kembalianNilai');
                const btnBayar = document.getElementById('btnBayar');
                if (input === '' || isNaN(input)) {
                    preview.style.display = 'none';
                    btnBayar.disabled = false;
                    return;
                }
                const uang = parseInt(input);
                const kembalian = uang - total;
                preview.style.display = 'block';
                if (kembalian < 0) {
                    preview.className = 'kembalian-preview kurang';
                    label.textContent = '⚠️ Uang kurang';
                    nilai.textContent = 'Kurang ' + formatRp(Math.abs(kembalian));
                    btnBayar.disabled = true;
                } else {
                    preview.className = 'kembalian-preview cukup';
                    label.textContent = '✅ Kembalian';
                    nilai.textContent = formatRp(kembalian);
                    btnBayar.disabled = false;
                }
            }
        </script>

    <?php else: ?>
        <div class="struk">
            <?php if ($mode_cetak_ulang): ?>
                <div class="badge-ulang">🔁 Cetak Ulang Struk</div>
            <?php endif; ?>
            <div class="center">
                <h3>KALA SERASA</h3>
                <p>Terima Kasih</p>
            </div>
            <div class="line"></div>
            <p>No. Nota : <?= htmlspecialchars($no_nota) ?></p>
            <p>Tanggal : <?= $tanggal ?></p>
            <p>Pelanggan: <?= htmlspecialchars($pelanggan) ?></p>
            <p>Kasir : <?= htmlspecialchars($kasir) ?></p>
            <div class="line"></div>
            <?php foreach ($items as $item):
                $nama  = htmlspecialchars($item['nama']);
                $harga = (int) $item['harga'];
                $qty   = (int) $item['qty'];
                $sub   = $harga * $qty;
            ?>
                <div class="item-row">
                    <span class="item-nama"><?= $nama ?></span>
                    <span class="item-qty"><?= $qty ?>x</span>
                    <span class="item-sub">Rp<?= number_format($sub, 0, ',', '.') ?></span>
                </div>
            <?php endforeach; ?>
            <div class="line"></div>
            <div class="item-row">
                <span><b>Total</b></span>
                <span><b>Rp<?= number_format($total, 0, ',', '.') ?></b></span>
            </div>
            <?php if (!$mode_cetak_ulang): ?>
                <div class="item-row">
                    <span>Uang Bayar</span>
                    <span>Rp<?= number_format($uang_bayar, 0, ',', '.') ?></span>
                </div>
                <div class="line"></div>
                <div class="kembalian-box">
                    <span>Kembalian : Rp<?= number_format($kembalian, 0, ',', '.') ?></span>
                </div>
            <?php endif; ?>
            <div class="line"></div>
            <div class="center">
                <p>Terima kasih sudah membeli!</p>
            </div>
            <button onclick="window.print()">🖨️ Print Struk</button>
            <?php if ($mode_cetak_ulang): ?>
                <button class="kembali" onclick="window.location='laporan.php'">← Kembali ke Laporan</button>
            <?php else: ?>
                <button class="kembali" onclick="window.location='user_home.php'">← Kembali</button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</body>

</html>