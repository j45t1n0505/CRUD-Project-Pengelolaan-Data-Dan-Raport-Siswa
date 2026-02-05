<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';
$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
        $error = 'Semua kolom harus diisi.';
    } elseif ($new_pass !== $confirm_pass) {
        $error = 'Password baru dan konfirmasi tidak sama.';
    } elseif (strlen($new_pass) < 6) {
        $error = 'Password harus minimal 6 karakter.';
    } else {
        // Verifikasi password lama
        $stmt = mysqli_prepare($koneksi, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $db_pass);
        $fetch_result = mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($fetch_result && $db_pass !== null && password_verify($old_pass, $db_pass)) {
            // Update password
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = mysqli_prepare($koneksi, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($update, "si", $hashed_pass, $user_id);
            
            if (mysqli_stmt_execute($update)) {
                $success = 'Password berhasil diubah!';
            } else {
                $error = 'Gagal mengubah password.';
            }
            mysqli_stmt_close($update);
        } else {
            $error = 'Password lama tidak sesuai.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #0d0d0d;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
            overflow-x: hidden;
        }

        .password-container {
            background: rgba(20, 0, 0, 0.95);
            border-radius: 18px;
            padding: 3rem;
            box-shadow: 0 0 40px 5px #ff003c, 0 0 80px 10px #ff003c44;
            width: 100%;
            max-width: 450px;
            animation: neonPop 1s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            border: 2px solid #ff003c;
        }

        h3 {
            color: #ff003c;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            text-shadow: 0 0 8px #ff003c, 0 0 20px #ff003c99;
            letter-spacing: 2px;
            animation: neonText 1.2s ease-in-out alternate infinite;
        }

        .form-label {
            font-weight: 600;
            color: #ff003c;
            text-shadow: 0 0 6px #ff003c99;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #ff003c;
            padding: 0.8rem;
            transition: all 0.3s ease;
            background-color: #1a000a;
            color: #fff;
            box-shadow: 0 0 10px #ff003c33 inset;
        }

        .form-control:focus {
            border-color: #fff;
            box-shadow: 0 0 0 0.2rem #ff003c99, 0 0 10px #ff003c99;
            transform: scale(1.03);
            background-color: #2a001a;
            color: #fff;
        }

        .btn {
            border-radius: 8px;
            padding: 0.8rem;
            font-weight: 700;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: #ff003c55;
            box-shadow: 0 0 40px #ff003c, 0 0 80px #ff003c99;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            z-index: 1;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
            filter: blur(8px);
        }

        .btn > * {
            position: relative;
            z-index: 2;
        }

        .btn-primary {
            background: #ff003c;
            border: none;
            width: 100%;
            margin-top: 1rem;
            color: #fff;
            box-shadow: 0 0 20px #ff003c99, 0 0 40px #ff003c33;
            text-shadow: 0 0 8px #fff, 0 0 20px #ff003c;
        }

        .btn-primary:hover {
            transform: scale(1.07) translateY(-3px);
            box-shadow: 0 0 40px #ff003c, 0 0 80px #ff003c99;
            filter: brightness(1.2);
        }

        .btn-secondary {
            background: #1a000a;
            color: #ff003c;
            border: 1.5px solid #ff003c;
            width: 100%;
            box-shadow: 0 0 10px #ff003c44;
        }

        .btn-secondary:hover {
            background: #ff003c;
            color: #fff;
            transform: scale(1.07) translateY(-3px);
            box-shadow: 0 0 40px #ff003c, 0 0 80px #ff003c99;
        }

        .alert {
            border-radius: 8px;
            border: 2px solid #ff003c;
            margin-bottom: 1.5rem;
            background: rgba(255, 0, 60, 0.1);
            color: #ff003c;
            text-shadow: 0 0 6px #ff003c99;
            box-shadow: 0 0 10px #ff003c33;
        }

        .alert-success {
            border-color: #38ef7d !important;
            background: rgba(56, 239, 125, 0.1) !important;
            color: #38ef7d !important;
            text-shadow: 0 0 6px #38ef7d99 !important;
        }

        .text-muted {
            font-size: 0.9rem;
            color: #ff003c !important;
            text-shadow: 0 0 6px #ff003c99;
        }

        @keyframes neonPop {
            0% {
                opacity: 0;
                transform: scale(0.7) rotate(-10deg);
                box-shadow: 0 0 0 #ff003c00;
            }
            80% {
                opacity: 1;
                transform: scale(1.05) rotate(2deg);
                box-shadow: 0 0 80px #ff003c, 0 0 120px #ff003c99;
            }
            100% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
                box-shadow: 0 0 40px #ff003c, 0 0 80px #ff003c44;
            }
        }

        @keyframes neonText {
            0% {
                text-shadow: 0 0 8px #ff003c, 0 0 20px #ff003c99, 0 0 40px #fff0;
            }
            100% {
                text-shadow: 0 0 20px #ff003c, 0 0 40px #ff003c99, 0 0 80px #ff003c;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .password-container {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="password-container">
        <h3><i class="fas fa-lock"></i> Ubah Password</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Password Lama</label>
                <input type="password" name="old_password" class="form-control password-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input type="password" name="new_password" class="form-control password-input" required>
                <small class="text-muted">Minimal 6 karakter</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" class="form-control password-input" required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="showPasswordChange">
                <label class="form-check-label" for="showPasswordChange">Tampilkan password</label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Ubah Password
            </button>
        </form>

        <hr>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var chk = document.getElementById('showPasswordChange');
    if (chk) {
        chk.addEventListener('change', function(){
            document.querySelectorAll('input.password-input').forEach(function(el){
                el.type = chk.checked ? 'text' : 'password';
            });
        });
    }
});
</script>