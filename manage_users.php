<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Hanya admin yang bisa akses halaman ini
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'koneksi.php';

$error = '';
$success = '';

// Hapus user
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id === $_SESSION['user']['id']) {
        $error = 'Anda tidak bisa menghapus akun sendiri.';
    } else {
        if (mysqli_query($koneksi, "DELETE FROM users WHERE id = $id")) {
            $success = 'User berhasil dihapus.';
        } else {
            $error = 'Gagal menghapus user.';
        }
    }
}

// Tambah user baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_user'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $name = trim($_POST['name']);

    if (empty($username) || empty($password) || empty($name)) {
        $error = 'Semua field harus diisi.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $check = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = 'Username sudah digunakan.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            if (mysqli_query($koneksi, "INSERT INTO users (username, password, name, role) VALUES ('$username', '$hashed', '$name', 'user')")) {
                $success = 'User berhasil ditambahkan.';
            } else {
                $error = 'Gagal menambahkan user.';
            }
        }
    }
}

// Ambil semua user
$users = mysqli_query($koneksi, "SELECT id, username, name, role, created_at FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-weight: 700;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            border: none;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 700;
            padding: 1.2rem;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }

        .role-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .role-admin {
            background: #ff6b6b;
            color: white;
        }

        .role-user {
            background: #4ecdc4;
            color: white;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 1.5rem;
            }

            .header-section {
                padding: 1.5rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-users"></i> Manajemen User</h2>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

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

        <!-- Form Tambah User -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-plus"></i> Tambah User Baru
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control password-input" required>
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" id="showPasswordManage">
                                    <label class="form-check-label" for="showPasswordManage">Tampilkan password</label>
                                </div>
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="tambah_user" class="btn btn-success">
                        <i class="fas fa-save"></i> Tambah User
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel User -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list"></i> Daftar User
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> No</th>
                                <th><i class="fas fa-user"></i> Username</th>
                                <th><i class="fas fa-id-card"></i> Nama</th>
                                <th><i class="fas fa-user-tag"></i> Role</th>
                                <th><i class="fas fa-calendar"></i> Dibuat</th>
                                <th><i class="fas fa-cog"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($users)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong><?php echo $row['username']; ?></strong></td>
                                <td><?php echo $row['name']; ?></td>
                                <td>
                                    <span class="role-badge <?php echo $row['role'] === 'admin' ? 'role-admin' : 'role-user'; ?>">
                                        <i class="fas <?php echo $row['role'] === 'admin' ? 'fa-shield-alt' : 'fa-user-circle'; ?>"></i>
                                        <?php echo ucfirst($row['role']); ?>
                                    </span>
                                </td>
                                <td><small><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></small></td>
                                <td>
                                    <?php if ($row['id'] !== $_SESSION['user']['id']): ?>
                                        <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Akun Anda</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        var chk = document.getElementById('showPasswordManage');
        if (chk) {
            chk.addEventListener('change', function(){
                var p = document.querySelector('input.password-input[name="password"]');
                if (p) p.type = this.checked ? 'text' : 'password';
            });
        }
    });
    </script>
</body>
</html>