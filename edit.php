<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id='$id'");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $alamat = $_POST['alamat'];

    $query = "UPDATE siswa SET nim='$nim', nama='$nama', jurusan='$jurusan', alamat='$alamat' WHERE id='$id'";
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal update data.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        body {
            background: #0d0d0d;
            min-height: 100vh;
            padding: 2rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            overflow-x: hidden;
        }

        .form-container {
            animation: neonPop 1s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            background: rgba(20, 0, 0, 0.95);
            border-radius: 18px;
            padding: 3rem;
            box-shadow: 0 0 40px 5px #ff003c, 0 0 80px 10px #ff003c44, 0 20px 60px rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            max-width: 520px;
            width: 100%;
            margin: 0 auto;
            border: 2px solid #ff003c;
        }

        h3 {
            color: #ff003c;
            font-weight: 700;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-align: center;
            text-shadow: 0 0 8px #ff003c, 0 0 20px #ff003c99, 0 0 40px #fff0;
            letter-spacing: 2px;
            animation: neonText 1.2s ease-in-out alternate infinite;
        }

        .form-group {
            margin-bottom: 1.5rem;
            animation: formSlide 0.8s cubic-bezier(0.68, -0.55, 0.27, 1.55) backwards;
            background: rgba(30,0,0,0.3);
            border-radius: 10px;
            box-shadow: 0 0 10px #ff003c33;
            padding: 1rem 1rem 0.5rem 1rem;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }

        label {
            font-weight: 600;
            color: #ff003c;
            margin-bottom: 0.7rem;
            display: block;
            font-size: 1rem;
            text-shadow: 0 0 6px #ff003c99;
            letter-spacing: 1px;
        }

        .form-control, .form-select {
            border: 2px solid #ff003c;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #1a000a;
            color: #fff;
            box-shadow: 0 0 10px #ff003c33 inset;
        }

        .form-control:focus, .form-select:focus {
            border-color: #fff;
            box-shadow: 0 0 0 0.2rem #ff003c99, 0 0 10px #ff003c99;
            transform: scale(1.03) translateY(-2px);
            background-color: #2a001a;
            color: #fff;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            animation: formSlide 0.6s ease-out 0.5s backwards;
        }

        .btn {
            padding: 0.9rem 1.8rem;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            flex: 1;
            text-align: center;
            position: relative;
            overflow: hidden;
            background: #ff003c;
            color: #fff;
            box-shadow: 0 0 20px #ff003c99, 0 0 40px #ff003c33;
            text-shadow: 0 0 8px #fff, 0 0 20px #ff003c;
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
            color: #fff;
            box-shadow: 0 0 20px #ff003c99, 0 0 40px #ff003c33;
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
            box-shadow: 0 0 10px #ff003c44;
        }

        .btn-secondary:hover {
            transform: scale(1.07) translateY(-3px) rotate(2deg);
            background: #ff003c;
            color: #fff;
            box-shadow: 0 0 40px #ff003c, 0 0 80px #ff003c99;
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
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                filter: blur(8px);
            }
            to {
                opacity: 1;
                filter: blur(0);
            }
        }
        @keyframes formSlide {
            from {
                opacity: 0;
                transform: translateX(-40px) scale(0.95) skewY(-2deg);
                filter: blur(8px);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1) skewY(0deg);
                filter: blur(0);
            }
        }

        @media (max-width: 576px) {
            .form-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            h3 {
                font-size: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3><i class="fas fa-edit"></i> Edit Data Siswa</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nim"><i class="fas fa-id-card"></i> NIM</label>
                <input type="text" id="nim" name="nim" class="form-control" value="<?php echo $row['nim']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?php echo $row['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label for="jurusan"><i class="fas fa-book"></i> Jurusan</label>
                <select id="jurusan" name="jurusan" class="form-select">
                    <option value="RPL" <?php if($row['jurusan'] == 'RPL') echo 'selected'; ?>>Rekayasa Perangkat Lunak</option>
                    <option value="TKJ" <?php if($row['jurusan'] == 'TKJ') echo 'selected'; ?>>Teknik Komputer Jaringan</option>
                    <option value="Multimedia" <?php if($row['jurusan'] == 'Multimedia') echo 'selected'; ?>>Multimedia</option>
                </select>
            </div>
            <div class="form-group">
                <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" required><?php echo $row['alamat']; ?></textarea>
            </div>
            <div class="button-group">
                <button type="submit" name="update" class="btn btn-primary">
                    <i class="fas fa-check"></i> Update Data
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</body>
</html>