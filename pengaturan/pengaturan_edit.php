<?php
session_start();
include 'pengaturan_koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: /Kala_Serasa/login.php");
    exit;
}

// Cek id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['toast'] = [
        'type'    => 'error',
        'message' => 'ID user tidak ditemukan!'
    ];
    header("Location: /Kala_Serasa/pengaturan.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data user berdasarkan id
$stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    $_SESSION['toast'] = [
        'type'    => 'error',
        'message' => 'Data user tidak ditemukan!'
    ];
    header("Location: /Kala_Serasa/pengaturan.php");
    exit;
}

// Proses update jika form disubmit
if (isset($_POST['update'])) {
    $username = trim($_POST['username'] ?? '');
    $role     = trim($_POST['role'] ?? '');
    $password = $_POST['password'] ?? '';

    // Role yang boleh dipilih
    $role_diizinkan = ['admin', 'user'];

    // Validasi username
    if ($username == '') {
        $_SESSION['toast'] = [
            'type'    => 'error',
            'message' => 'Username tidak boleh kosong!'
        ];
        header("Location: pengaturan_edit.php?id=$id");
        exit;
    }

    if (strlen($username) < 4 || strlen($username) > 20) {
        $_SESSION['toast'] = [
            'type'    => 'error',
            'message' => 'Username harus 4-20 karakter!'
        ];
        header("Location: pengaturan_edit.php?id=$id");
        exit;
    }

    if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
        $_SESSION['toast'] = [
            'type'    => 'error',
            'message' => 'Username hanya boleh huruf, angka, dan underscore!'
        ];
        header("Location: pengaturan_edit.php?id=$id");
        exit;
    }

    // Validasi role
    if (!in_array($role, $role_diizinkan, true)) {
        $_SESSION['toast'] = [
            'type'    => 'error',
            'message' => 'Role tidak valid!'
        ];
        header("Location: pengaturan_edit.php?id=$id");
        exit;
    }

    // Jika password diisi, validasi password
    if (!empty($password)) {
        if (strlen($password) < 6 || strlen($password) > 10) {
            $_SESSION['toast'] = [
                'type'    => 'error',
                'message' => 'Password harus 6-10 karakter!'
            ];
            header("Location: pengaturan_edit.php?id=$id");
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($koneksi, "UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $username, $hash, $role, $id);
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET username = ?, role = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $username, $role, $id);
    }

    $update = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($update) {
        $_SESSION['toast'] = [
            'type'    => 'success',
            'message' => 'Data user berhasil diperbarui!'
        ];
    } else {
        $_SESSION['toast'] = [
            'type'    => 'error',
            'message' => 'Gagal memperbarui data!'
        ];
    }

    header("Location: /Kala_Serasa/pengaturan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit User</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary-color: #5f2d22;
            --gradient-primary: linear-gradient(135deg, #9e5c3d 0%, #4e2818 100%);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        body {
            background: var(--gradient-primary);
            margin: 0;
        }

        h1 {
            font-size: 3rem;
            text-transform: uppercase;
            color: #fff;
            text-align: center;
            padding: 2rem 0 1rem;
        }

        .base {
            width: 400px;
            padding: 40px 70px 60px;
            margin: 0 auto 7rem;
            border-radius: 10px;
            background-color: #ededed;
            box-shadow: var(--shadow-xl);
        }

        label {
            margin-top: 10px;
            float: left;
            width: 100%;
            font-weight: 500;
        }

        input,
        select {
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            background-color: #f8f8f8;
            border: 2px solid #ccc;
            outline-color: var(--primary-color);
            border-radius: 8px;
        }

        input:hover,
        select:hover {
            transform: translateY(2px);
            box-shadow: var(--shadow-lg);
            transition: all 0.2s ease;
        }

        .info {
            font-size: 11px;
            color: #888;
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

        .btn-back {
            background: #6b7280;
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <h1>Edit User</h1>

    <section class="base">
        <form method="POST">
            <label>Username</label>
            <input
                type="text"
                name="username"
                value="<?= htmlspecialchars($row['username']); ?>"
                minlength="4"
                maxlength="20"
                pattern="[A-Za-z0-9_]+"
                title="Username hanya boleh huruf, angka, dan underscore"
                required>
            <br><br>

            <label>
                Password Baru
                <span class="info">(opsional)</span>
            </label>
            <input
                type="password"
                name="password"
                minlength="6"
                maxlength="10"
                placeholder="Kosongkan jika tidak ingin mengubah password">
            <br><br>

            <label>Role</label>
            <select name="role" required>
                <option value="user" <?= strtolower($row['role']) == 'user' ? 'selected' : ''; ?>>
                    User
                </option>

                <option value="admin" <?= strtolower($row['role']) == 'admin' ? 'selected' : ''; ?>>
                    Admin
                </option>
            </select>
            <br><br>

            <button type="submit" name="update">Update</button>
            <button type="button" class="btn-back" onclick="history.back()">Kembali</button>
        </form>
    </section>

</body>

</html>