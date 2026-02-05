<?php
session_start();
include 'koneksi.php';
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = 'Masukkan username dan password.';
    } else {
        $stmt = mysqli_prepare($koneksi, "SELECT id, username, password, name, role FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $dbuser, $dbpass, $name, $role);

        if (mysqli_stmt_fetch($stmt) && $dbpass !== null) {
            if (password_verify($password, $dbpass)) {
                $_SESSION['user'] = ['id' => $id, 'username' => $dbuser, 'name' => $name, 'role' => $role];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Username atau password salah.';
            }
        } else {
            $error = 'Username tidak ditemukan.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Aplikasi Pendidikan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body style="background: #0d0d0d; min-height:100vh; display:flex; align-items:center; justify-content:center; overflow-x:hidden;">
    <div class="card p-4" style="width: 380px; border-radius:12px; box-shadow:0 0 40px 5px #ff003c, 0 0 80px 10px #ff003c44; border:2px solid #ff003c; background:#1a000a;">
        <h4 class="mb-3 text-center" style="color: #ff003c; text-shadow: 0 0 8px #ff003c, 0 0 20px #ff003c99; letter-spacing:2px;">Selamat Datang Di Website Pengelolaan Data Siswa <br> SMK Al - Madani <br></h4>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label" style="color: #ff003c; text-shadow: 0 0 6px #ff003c99;">Username</label>
                <input type="text" name="username" class="form-control" style="border:2px solid #ff003c; background:#1a000a; color:#fff; box-shadow:0 0 10px #ff003c33 inset;" required>
            </div>
            <div class="mb-3">
                <label class="form-label" style="color: #ff003c; text-shadow: 0 0 6px #ff003c99;">Password</label>
                <input type="password" name="password" class="form-control password-input" style="border:2px solid #ff003c; background:#1a000a; color:#fff; box-shadow:0 0 10px #ff003c33 inset;" required>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" id="showPasswordLogin">
                    <label class="form-check-label" for="showPasswordLogin" style="color:#ff003c;">Tampilkan password</label>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary" style="background: #ff003c; color:#fff; box-shadow:0 0 20px #ff003c99, 0 0 40px #ff003c33; text-shadow:0 0 8px #fff, 0 0 20px #ff003c; font-weight:700; border:none; transition:all 0.3s; position:relative; overflow:hidden;">LOGIN</button>
            </div>
        </form>
        <hr>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var chk = document.getElementById('showPasswordLogin');
    if (chk) {
        chk.addEventListener('change', function(){
            var p = document.querySelector('input.password-input[name="password"]');
            if (p) p.type = this.checked ? 'text' : 'password';
        });
    }
});
</script>
</html>