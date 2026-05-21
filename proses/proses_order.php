<?php
session_start();
include('../koneksi.php');

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../user_home.php");
    exit;
}

$pelanggan  = isset($_POST['pelanggan']) ? trim($_POST['pelanggan']) : '';
$items_json = isset($_POST['items_json']) ? $_POST['items_json'] : '';
$kasir      = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';

if ($pelanggan === '' || $items_json === '') {
    echo "<script>
        alert('Data pesanan tidak lengkap');
        window.location='../user_home.php';
    </script>";
    exit;
}

$items = json_decode($items_json, true);

if (!$items || !is_array($items) || count($items) === 0) {
    echo "<script>
        alert('Pesanan masih kosong');
        window.location='../user_home.php';
    </script>";
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    $total_hitung = 0;
    $items_valid = [];

    foreach ($items as $item) {
        $id_menu = isset($item['id']) ? (int) $item['id'] : 0;
        $qty     = isset($item['qty']) ? (int) $item['qty'] : 0;

        if ($id_menu <= 0 || $qty <= 0) {
            throw new Exception("Data item tidak valid");
        }

        $q_menu = mysqli_query($koneksi, "
            SELECT * FROM menu
            WHERE id = '$id_menu'
            FOR UPDATE
        ");

        if (!$q_menu) {
            throw new Exception("Gagal mengambil data menu: " . mysqli_error($koneksi));
        }

        if (mysqli_num_rows($q_menu) === 0) {
            throw new Exception("Menu tidak ditemukan");
        }

        $menu = mysqli_fetch_assoc($q_menu);

        $nama_menu  = $menu['nama_menu'];
        $harga_menu = (int) $menu['harga_menu'];
        $stok       = isset($menu['stok']) ? (int) $menu['stok'] : 0;
        $status     = strtolower(trim($menu['status']));

        if ($status === 'habis' || $stok <= 0) {
            throw new Exception("Menu " . $nama_menu . " sedang habis");
        }

        if ($qty > $stok) {
            throw new Exception("Stok " . $nama_menu . " tidak cukup. Stok tersedia hanya " . $stok . " porsi");
        }

        $subtotal = $harga_menu * $qty;
        $total_hitung += $subtotal;

        $items_valid[] = [
            'id'    => $id_menu,
            'nama'  => $nama_menu,
            'harga' => $harga_menu,
            'qty'   => $qty
        ];
    }

    if ($total_hitung <= 0) {
        throw new Exception("Total pesanan tidak valid");
    }

    $no_nota = 'KS' . date('YmdHis') . rand(100, 999);

    $no_nota_safe   = mysqli_real_escape_string($koneksi, $no_nota);
    $pelanggan_safe = mysqli_real_escape_string($koneksi, $pelanggan);
    $kasir_safe     = mysqli_real_escape_string($koneksi, $kasir);

    $q_transaksi = mysqli_query($koneksi, "
        INSERT INTO transaksi (no_nota, nama_pelanggan, total_harga, kasir, tanggal)
        VALUES ('$no_nota_safe', '$pelanggan_safe', '$total_hitung', '$kasir_safe', NOW())
    ");

    if (!$q_transaksi) {
        throw new Exception("Gagal menyimpan transaksi: " . mysqli_error($koneksi));
    }

    $id_transaksi = mysqli_insert_id($koneksi);

    foreach ($items_valid as $item) {
        $id_menu = (int) $item['id'];
        $nama    = mysqli_real_escape_string($koneksi, $item['nama']);
        $harga   = (int) $item['harga'];
        $qty     = (int) $item['qty'];

        $q_detail = mysqli_query($koneksi, "
            INSERT INTO detail_transaksi (id_transaksi, nama_menu, harga_menu, qty)
            VALUES ('$id_transaksi', '$nama', '$harga', '$qty')
        ");

        if (!$q_detail) {
            throw new Exception("Gagal menyimpan detail transaksi: " . mysqli_error($koneksi));
        }

        $q_update_stok = mysqli_query($koneksi, "
            UPDATE menu
            SET 
                stok = stok - $qty,
                status = CASE
                    WHEN stok - $qty <= 0 THEN 'habis'
                    ELSE 'tersedia'
                END
            WHERE id = '$id_menu'
            AND stok >= $qty
        ");

        if (!$q_update_stok) {
            throw new Exception("Gagal mengurangi stok: " . mysqli_error($koneksi));
        }

        if (mysqli_affected_rows($koneksi) <= 0) {
            throw new Exception("Stok tidak cukup saat transaksi diproses");
        }
    }

    $_SESSION['struk'] = [
        'pelanggan' => $pelanggan,
        'total'     => $total_hitung,
        'items'     => $items_valid,
        'no_nota'   => $no_nota,
        'kasir'     => $kasir
    ];

    mysqli_commit($koneksi);

    header("Location: ../struk.php");
    exit;
} catch (Exception $e) {
    mysqli_rollback($koneksi);

    $pesan = json_encode("Gagal memproses pesanan: " . $e->getMessage());

    echo "<script>
        alert($pesan);
        window.location='../user_home.php';
    </script>";
    exit;
}
