<?php
session_start();
include('koneksi.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <!-- Google Fonts  -->
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
            color: #fff;
            text-transform: uppercase;
        }

        label {
            margin-top: 10px;
            float: left;
            text-align: left;
            width: 100%;
        }

        .base {
            width: 500px;
            padding: 70px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 10px;
            background-color: #ededed;
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
        }

        button:hover {
            transform: translateY(2px);
            box-shadow: var(--shadow-xl);
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #19c54a;
            color: white;
            padding: 12px 20px;
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
    </style>
</head>

<body>
    <center>
        <h1>Tambah Produk</h1>
    </center>
    <form method="POST" action="proses/proses_tambah_produk.php" enctype="multipart/form-data">
        <section class="base">
            <div>
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" autofocus="" required="" />
            </div>
            <div>
                <label>Deskripsi</label>
                <input type="text" name="deskripsi" />
            </div>
            <div>
                <label>Harga </label>
                <input type="text" name="harga" required="" />
            </div>
            <div>
                <label>Gambar</label>
                <input type="file" name="gambar" required="" />
            </div>
            <div>
                <label>Status</label>
                <select name="status" required="">
                    <option value="tersedia" <?php if (isset($data['status']) && $data['status'] == 'tersedia') echo 'selected'; ?>>Tersedia</option>
                    <option value="habis" <?php if (isset($data['status']) && $data['status'] == 'habis') echo 'selected'; ?>>Habis</option>
                </select>
                <button type="submit"> Simpan Produk</button>
                <button style="margin-left: 10px;" type="button" onclick="history.back()" class="btn btn-secondary">Kembali</button>
            </div>
        </section>
    </form>

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

</body>

</html>