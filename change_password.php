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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .password-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            animation: slideUp 0.6s ease-out;
        }

        h3 {
            color: #333;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-label {
            font-weight: 600;
            color: #555;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 0.8rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn {
            border-radius: 8px;
            padding: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
            border: none;
            width: 100%;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .text-muted {
            font-size: 0.9rem;
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