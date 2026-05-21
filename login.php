<?php
session_start();

if (isset($_SESSION['login'])) {
  if ($_SESSION['role'] == 'admin') {
    header('Location: index.php');
  } else {
    header('Location: user_home.php');
  }
  exit;
}

// generate captcha
$angka1 = rand(1, 9);
$angka2 = rand(1, 9);
$_SESSION['captcha'] = $angka1 + $angka2;
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>

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
      width: 93%;
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
      width: 100%;
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
  </style>
</head>

<body>



  <!-- LOGIN -->
  <form class="box" action="proses_login.php" method="POST">
    <div class="container">
      <img src="assets/logo.png" alt="logo">
      <div class="form-area">
        <h2>LOGIN</h2>
        <h5>Masukkan username dan password anda</h5>
        <input name="username" type="text" placeholder="Username" required>
        <input name="password" type="password" placeholder="Password" required>
        <label style="color: black; font-family: 'Poppins', sans-serif;">Berapa <?= $angka1 ?> + <?= $angka2 ?> ?</label>
        <input name="captcha" type="number" placeholder="Jawaban captcha" required>
        <button class="btn" type="submit" name="submit_validate" value="abc">LOGIN</button>
        <h5>Belum memiliki akun? <a href="register"> Register</a></h5>
      </div>
    </div>
  </form>
</body>

</html>