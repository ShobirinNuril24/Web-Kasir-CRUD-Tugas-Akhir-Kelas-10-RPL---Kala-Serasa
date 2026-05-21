<?php
session_start();
include "regist_koneksi.php";
if ($_POST['captcha'] != $_SESSION['captcha']) {
    echo "<script>alert('Captcha salah!'); window.history.back();</script>";
    exit;
}

unset($_SESSION['captcha']);

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$data = mysqli_fetch_assoc($query);

if($data && password_verify($password, $data['password'])){

    $_SESSION['login'] = true;
    $_SESSION['id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    if($data['role'] == 'admin'){
        header('Location: index.php');
    } else {
        header('Location: user_home.php');
    }
    exit;

} else {
?>
<script>
alert('Username atau password salah');
window.location='login.php';
</script>
<?php
}
?>