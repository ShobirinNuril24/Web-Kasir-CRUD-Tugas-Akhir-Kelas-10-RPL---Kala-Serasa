<?php
session_start();
require "regist_koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Register</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
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
      margin: 0;
      background: var(--gradient-primary);
      height: 38rem;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    img {
      width: 270px;
      padding-top: 50px;
      margin-left: 20px;
    }

    .container {
      display: flex;
      align-items: center;
      width: 100%;
    }

    .box {
      background: linear-gradient(135deg, #ffe4d3 0%, #dcc3b4 100%);
      padding: 20px;
      border-radius: 10px;
      width: 700px;
      height: 400px;
      color: white;
    }

    .form-area {
      padding-top: 25px;
      margin-left: 80px;
      display: flex;
      flex-direction: column;
      gap: 1px;
    }

    h2 {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding-top: 20px;
      font-size: 28px;
      color: var(--dark-color);
    }

    h5 {
      font-family: sans-serif;
      margin: 0;
      padding-top: 10px;
      padding-bottom: 15px;
      font-size: 14px;
      color: var(--dark-color);
    }

    a {
      color: var(--primary-color);
      text-decoration: none;
    }

    a:hover {
      color: var(--dark-color);
      text-decoration: underline;
    }

    input {
      width: 107%;
      padding: 10px;
      margin: 8px 0;
      border: none;
      outline: none;
      border-radius: 3px;
      box-shadow: var(--shadow-md);
    }

    input:hover {
      outline: 2px solid var(--primary-color);
    }

    .btn {
      width: 115%;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 18px;
      background: #512d0f;
      box-shadow: var(--shadow-md);
      color: white;
      border: none;
      cursor: pointer;
    }

    .btn:hover {
      background: #381e08;
      box-shadow: var(--shadow-md);
    }

    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
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

  <form class="box" action="" method="post">
    <div class="container">
      <img src="assets/logo.png" alt="logo">
      <div class="form-area">
        <h2>REGISTER</h2>
        <h5>Buat username dan password anda</h5>
        <input id="username" name="username"
          type="text"
          placeholder="Username"
          minlength="4"
          maxlength="20"
          pattern="[a-zA-Z0-9_]+"
          title="Username harus 4-20 karakter dan hanya boleh huruf, angka, atau underscore"
          required>

        <input name="password" type="password" placeholder="Password (6-10 karakter)" minlength="6" maxlength="10" required>
        <button class="btn" type="submit" name="submit" value="register">REGISTER</button>
      </div>
    </div>
  </form>

  <?php
  if (isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $blockedusernames = [
      'admin',
      'administrator',
      'root',
      'owner',
      'test',
      'user',
      'guest',
      'null',
      'undefined',
    ];
    if ($username == '' || $password == '') {
      $_SESSION['toast'] = [
        'type'    => 'error',
        'message' => 'Username dan password tidak boleh kosong!'
      ];
      header("Location: register");
      exit;
      if (strlen($username) < 4 || strlen($username) > 20) {
        $_SESSION['toast'] = [
          'type'    => 'error',
          'message' => 'Username harus 4-20 karakter!'
        ];
        header("Location: register");
        exit;
      }
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
      $_SESSION['toast'] = [
        'type'    => 'error',
        'message' => 'Username hanya boleh huruf, angka, atau underscore!'
      ];
      header("Location: register");
      exit;
    }
    if (in_array(strtolower($username), $blockedusernames)) {
      $_SESSION['toast'] = [
        'type'    => 'error',
        'message' => 'Username tidak boleh menggunakan kata yang umum atau sensitif!'
      ];
      header("Location: register");
      exit;
    }

    if (strlen($password) < 6 || strlen($password) > 10) {
      $_SESSION['toast'] = [
        'type'    => 'error',
        'message' => 'Password harus 6-10 karakter!'
      ];
      header("Location: register");
      exit;
    }

    $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
    $count = mysqli_num_rows($query);

    if ($count > 0) {
      $_SESSION['toast'] = [
        'type'    => 'error',
        'message' => 'Username sudah digunakan, coba yang lain!'
      ];
      header("Location: register");
      exit;
    } else {
      $queryInsert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$encryptedPassword', 'user')");
      if ($queryInsert) {
        $_SESSION['toast'] = [
          'type'    => 'success',
          'message' => 'Berhasil membuat akun, silahkan login!'
        ];
        header("Location: login");
        exit;
      }
    }
  }
  ?>

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
        toast.style.background = data.type === 'success' ? '#10b981' : '#ef4444';
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
      });
    </script>
    <?php unset($_SESSION['toast']); ?>
  <?php endif; ?>
  <script>
    const username = document.getElementById("username");

    username.addEventListener("invalid", function() {
      if (username.value.length < 4) {
        username.setCustomValidity("Username minimal 4 karakter.");
      } else {
        username.setCustomValidity("Username hanya boleh huruf, angka, atau underscore.");
      }
    });

    username.addEventListener("input", function() {
      username.setCustomValidity("");
    });
  </script>

</body>

</html>