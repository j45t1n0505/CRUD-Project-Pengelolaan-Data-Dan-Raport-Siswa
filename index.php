<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi Pendidikan - Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1200px;
        }

        /* Header Animation */
        .header-section {
            animation: slideDown 0.6s ease-out;
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        h2 {
            color: #333;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0;
        }

        /* Button Animations */
        .btn-group-custom {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            border: none;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Table Animations */
        .table-container {
            animation: fadeIn 0.8s ease-out 0.2s both;
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .table {
            margin-bottom: 0;
            animation: tableSlideUp 0.6s ease-out;
        }

        .table tbody tr {
            animation: rowFadeIn 0.5s ease-out backwards;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.15s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.25s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.3s; }
        .table tbody tr:nth-child(6) { animation-delay: 0.35s; }
        .table tbody tr:nth-child(n+7) { animation-delay: 0.4s; }

        .table tbody tr:hover {
            background-color: #f0f4ff !important;
            transform: scale(1.01);
            box-shadow: inset 0 0 10px rgba(102, 126, 234, 0.1);
        }

        .table-dark {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .table thead th {
            border: none;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Button in Table */
        .btn-warning, .btn-danger, .btn-info {
            border-radius: 6px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border: none;
        }

        .btn-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
        }

        .btn-sm:hover {
            transform: translateY(-2px) scale(1.05);
        }

        /* Keyframe Animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes rowFadeIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes tableSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.95rem;
            color: #666;
        }

        .user-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            h2 {
                font-size: 1.8rem;
                margin-bottom: 1rem;
            }

            .btn-group-custom {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }

            .header-section {
                padding: 1.5rem;
            }

            .table-container {
                padding: 1rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }

            .user-info {
                margin-top: 1rem;
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2><i class="fas fa-graduation-cap"></i> Manajemen Sekolah</h2>
                    <div class="user-info" style="margin-top: 0.5rem;">
                        <small><strong>Pengguna:</strong> <span class="user-badge"><?php echo $_SESSION['user']['name']; ?></span></small>
                    </div>
                </div>
                <div class="btn-group-custom">
                    <a href="input_nilai.php" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Input Nilai Siswa
                    </a>
                    <a href="tambah.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Tambah Siswa Baru
                    </a>
                    <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <a href="manage_users.php" class="btn btn-info">
                        <i class="fas fa-users-cog"></i> Kelola User
                    </a>
                    <?php endif; ?>
                    <a href="change_password.php" class="btn btn-warning">
                        <i class="fas fa-lock"></i> Ubah Password
                    </a>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>


        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark text-center">
                        <tr>
                            <th><i class="fas fa-hashtag"></i> No</th>
                            <th><i class="fas fa-id-card"></i> NIM</th>
                            <th><i class="fas fa-user"></i> Nama</th>
                            <th><i class="fas fa-book"></i> Jurusan</th>
                            <th><i class="fas fa-cog"></i> Aksi Data</th>
                            <th><i class="fas fa-file-alt"></i> Aksi Raport</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM siswa ORDER BY id DESC";
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td class="text-center"><strong><?php echo $no++; ?></strong></td>
                            <td><?php echo $row['nim']; ?></td>
                            <td><strong><?php echo $row['nama']; ?></strong></td>
                            <td><?php echo $row['jurusan']; ?></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" title="Edit Data">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')" title="Hapus Data">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="raport.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm text-white" title="Lihat Raport">
                                    <i class="fas fa-file-pdf"></i> Raport
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
