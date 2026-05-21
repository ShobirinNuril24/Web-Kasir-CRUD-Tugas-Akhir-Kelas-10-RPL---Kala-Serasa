<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <title>Pengaturan</title>
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

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
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

        /* SEARCH & FILTER */
        .search-filter-wrap {
            margin-left: 12rem;
            margin-right: 12rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 12px;
            align-items: center;
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
            padding: 10px 14px 10px 40px;
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

        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            padding: 10px 16px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-color);
            white-space: nowrap;
        }

        .f-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
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
            margin-left: 12rem;
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 10px;
        }

        .hasil-info b {
            color: var(--primary-color);
        }

        .table-container {
            margin-left: 12rem;
            margin-right: 12rem;
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table thead {
            background: var(--primary-color);
        }

        .user-table th {
            padding: 1.25rem 1rem;
            text-align: left;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .user-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .user-table tbody tr:hover {
            background: #f9fafb;
        }

        .user-table td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-edit:hover {
            background: #3b82f6;
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete:hover {
            background: #ef4444;
            color: #fff;
            transform: translateY(-2px);
        }

        .toast {
            text-align: center;
            position: fixed;
            top: 20px;
            right: 20px;
            background: #19c54a;
            color: white;
            padding: 12px 20px;
            margin-right: 30rem;
            border-radius: 8px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            z-index: 9999;
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

            .table-container {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="assets/logo1.png" alt="logo1" style="height: 70px; width: 235px;">
                </div>
                <nav class="nav-menu">
                    <a href="index.php" class="nav-link"><i class="fas fa-box"></i> Produk</a>
                    <a href="#" class="nav-link"><i class="fas fa-chart-line"></i> Laporan</a>
                    <a href="pengaturan.php" class="nav-link active"><i class="fas fa-cog"></i> Pengaturan</a>
                    <a href="/Kala_Serasa/proses/proses_logout.php" class="nav-link"><i class="fas fa-sign-out"></i> Logout</a>
                </nav>
            </div>
        </div>
    </header>

    <center>
        <h1 style="color: #000000; padding-top: 3rem;"><b>Data Pengguna</b></h1>
    </center>
    <center>
        <p style="padding-bottom: 1rem;">Daftar Pengguna Kala Serasa</p>
    </center>

    <!-- SEARCH & FILTER -->
    <div class="search-filter-wrap">
        <div class="search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari username..." oninput="jalankanFilter()">
        </div>
        <div class="filter-bar">
            <span class="filter-label"><i class="fas fa-filter"></i>&nbsp; Role :</span>
            <button class="f-btn aktif" data-role="semua">Semua</button>
            <button class="f-btn" data-role="admin"><i class="fas fa-shield-alt"></i> Admin</button>
            <button class="f-btn" data-role="user"><i class="fas fa-user"></i> User</button>
        </div>
    </div>

    <div class="hasil-info">Menampilkan <b id="jumlahHasil">0</b> pengguna</div>

    <div class="table-container">
        <table class="user-table" id="userTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Username</th>
                    <th width="25%">Password</th>
                    <th width="20%">Role</th>
                    <th width="25%">Aksi</th>
                </tr>
            </thead>
            <tbody id="userBody">
                <?php
                include('pengaturan/pengaturan_koneksi.php');
                $data = mysqli_query($koneksi, "SELECT * FROM users");
                $no = 0;
                while ($baris = mysqli_fetch_array($data)) {
                    $no++;
                ?>
                    <tr class="user-row" data-username="<?= strtolower($baris['username']) ?>" data-role="<?= strtolower($baris['role']) ?>">
                        <td><?php echo $no ?></td>
                        <td><?php echo $baris['username'] ?></td>
                        <td><span style="letter-spacing: 3px;">••••••••</span></td>
                        <td><?= $baris['role'] ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="pengaturan/pengaturan_edit.php?id=<?= $baris['id']; ?>"
                                    class="btn-action btn-edit"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="pengaturan/pengaturan_hapus.php?id=<?= $baris['id']; ?>"
                                    class="btn-action btn-delete"
                                    onclick="return confirm('Yakin ingin menghapus pengguna \'<?= htmlspecialchars($baris['username']); ?>\'?')"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div id="toast" class="toast"></div>
    <?php if (isset($_SESSION['toast'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toast = document.getElementById('toast');
                const data = {
                    type: "<?= $_SESSION['toast']['type']; ?>",
                    message: "<?= $_SESSION['toast']['message']; ?>"
                };
                toast.innerText = data.message;
                if (data.type === 'success') {
                    toast.style.background = '#10b981';
                } else {
                    toast.style.background = '#ef4444';
                }
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            });
        </script>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

    <script>
        const semuaRow = Array.from(document.querySelectorAll('.user-row'));

        document.querySelectorAll('.f-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.f-btn').forEach(b => b.classList.remove('aktif'));
                this.classList.add('aktif');
                jalankanFilter();
            });
        });

        function jalankanFilter() {
            const filterRole = document.querySelector('.f-btn.aktif').dataset.role;
            const cari = document.getElementById('searchInput').value.toLowerCase().trim();
            let jumlah = 0;

            semuaRow.forEach(row => {
                const cocokRole = filterRole === 'semua' || row.dataset.role === filterRole;
                const cocokCari = cari === '' || row.dataset.username.includes(cari);
                if (cocokRole && cocokCari) {
                    row.style.display = '';
                    jumlah++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('jumlahHasil').textContent = jumlah;
        }

        window.addEventListener('DOMContentLoaded', jalankanFilter);
    </script>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 My Market. Dibuat dengan <i class="fas fa-heart" style="color:#ef4444;"></i> oleh Kala Serasa Group</p>
        </div>
    </footer>

</body>

</html>