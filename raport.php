<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';

// Cek apakah ada ID siswa yang dikirim
if (!isset($_GET['id'])) {
    header("Location: index.php");
}

$id_siswa = $_GET['id'];

// Ambil Data Siswa
$querySiswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id='$id_siswa'");
$dataSiswa = mysqli_fetch_assoc($querySiswa);
?>

<!DOCTYPE html>
<html>
<head>
    <title>E-Raport - <?php echo $dataSiswa['nama']; ?></title>
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
            overflow-x: hidden;
        }

        .raport-container {
            animation: neonPop 1s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            background: rgba(20, 0, 0, 0.95);
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 0 40px 5px #ff003c, 0 0 80px 10px #ff003c44;
            backdrop-filter: blur(10px);
            max-width: 900px;
            margin: 2rem auto;
            border: 2px solid #ff003c;
        }

        .header-raport {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeIn 0.6s ease-out;
        }

        .header-raport h2 {
            color: #ff003c;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 8px #ff003c, 0 0 20px #ff003c99;
        }

        .header-raport h5 {
            color: #ff003c;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 0 6px #ff003c99;
        }

        hr {
            border: 2px solid #ff003c;
            opacity: 0.5;
            margin: 2rem 0;
            animation: fadeIn 0.8s ease-out 0.1s backwards;
            box-shadow: 0 0 10px #ff003c99;
        }

        .student-info {
            animation: slideIn 0.6s ease-out 0.15s backwards;
            background: rgba(255, 0, 60, 0.1);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid #ff003c;
            box-shadow: 0 0 10px #ff003c33;
        }

        .student-info table {
            margin-bottom: 0;
        }

        .student-info td {
            padding: 0.8rem;
            border: none;
        }

        .student-info td:first-child {
            font-weight: 700;
            color: #ff003c;
            width: 150px;
            text-shadow: 0 0 6px #ff003c99;
        }

        .student-info strong {
            color: #ff003c;
            font-size: 1.1rem;
            text-shadow: 0 0 6px #ff003c99;
        }

        /* Table Animations */
        .nilai-container {
            animation: slideUp 0.6s ease-out 0.3s backwards;
            overflow: hidden;
            border-radius: 12px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: rgba(255, 0, 60, 0.1);
            animation: fadeIn 0.6s ease-out;
        }

        .table thead th {
            border: none;
            color: #ff003c;
            font-weight: 700;
            padding: 1.2rem 0.8rem;
            text-align: center;
            text-shadow: 0 0 6px #ff003c99;
        }

        .table tbody tr {
            animation: rowFadeIn 0.5s ease-out backwards;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-bottom: 1px solid #e0e0e0;
        }

        .table tbody tr:nth-child(1) { animation-delay: 0.35s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.4s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.45s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.5s; }
        .table tbody tr:nth-child(n+5) { animation-delay: 0.55s; }

        .table tbody tr:hover {
            background-color: rgba(255, 0, 60, 0.1);
            transform: scale(1.01);
            box-shadow: inset 0 0 15px rgba(255, 0, 60, 0.2);
        }

        .table tbody td {
            padding: 1rem 0.8rem;
            vertical-align: middle;
        }

        .table tbody td:first-child {
            text-align: center;
            font-weight: 700;
            color: #ff003c;
            text-shadow: 0 0 6px #ff003c99;
        }

        .table tbody td:nth-child(n+3):nth-child(-n+6) {
            text-align: center;
            font-weight: 600;
        }

        strong {
            color: #ff003c;
            text-shadow: 0 0 6px #ff003c99;
        }

        .text-success {
            color: #38ef7d !important;
            font-weight: 700;
            text-shadow: 0 0 6px #38ef7d99;
        }

        .text-danger {
            color: #ff6b6b !important;
            font-weight: 700;
            text-shadow: 0 0 6px #ff6b6b99;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
            animation: slideUp 0.6s ease-out 0.6s backwards;
            justify-content: center;
        }

        .btn {
            padding: 0.9rem 2rem;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            color: #fff;
            box-shadow: 0 0 20px #ff003c99, 0 0 40px #ff003c33;
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
            color: white;
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
            box-shadow: 0 0 10px #ff003c44;
        }

        .btn-secondary:hover {
            transform: scale(1.07) translateY(-3px);
            background: #ff003c;
            color: #fff;
            box-shadow: 0 0 40px #ff003c, 0 0 80px #ff003c99;
        }

        .no-print {
            animation: fadeIn 0.8s ease-out 0.7s backwards;
        }

        /* CSS untuk menyembunyikan tombol saat dicetak */
        @media print {
            .no-print { 
                display: none !important; 
            }

            body {
                background: white;
                padding: 0;
            }

            .raport-container {
                box-shadow: none;
                background: white;
                border: none;
                animation: none;
                color: black;
            }

            .raport-container h2,
            .raport-container h5,
            strong,
            .student-info td:first-child,
            .student-info strong,
            .table thead th {
                color: black !important;
                text-shadow: none !important;
            }

            .table tbody tr {
                animation: none;
            }

            .table tbody tr:hover {
                background-color: inherit;
                transform: none;
                box-shadow: none;
            }
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

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
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

        @media (max-width: 768px) {
            .raport-container {
                padding: 1.5rem;
                margin: 1rem;
            }

            .header-raport h2 {
                font-size: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .table-responsive {
                font-size: 0.9rem;
            }

            .table thead th {
                padding: 0.8rem 0.4rem;
            }

            .table tbody td {
                padding: 0.8rem 0.4rem;
            }
        }
    </style>
</head>
<body>
    <div class="raport-container">
        <div class="header-raport">
            <h2><img src="images.webp" alt="Logo" style="height: 5rem; margin-right: 0.8rem; vertical-align: middle;"> Laporan Hasil Belajar (E-Raport)</h2>
            <h5>SMK Al - Madani Pontianak Tenggara</h5>
        </div>

        <hr>

        <div class="student-info">
            <table class="table table-borderless">
                <tr>
                    <td><i class="fas fa-user"></i> Nama Siswa</td>
                    <td>: <strong><?php echo $dataSiswa['nama']; ?></strong></td>
                </tr>
                <tr>
                    <td><i class="fas fa-id-card"></i> NIM</td>
                    <td>: <?php echo $dataSiswa['nim']; ?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-graduation-cap"></i> Jurusan</td>
                    <td>: <?php echo $dataSiswa['jurusan']; ?></td>
                </tr>
            </table>
        </div>

        <div class="nilai-container">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-3">
                    <thead class="table-dark text-center">
                        <tr>
                            <th><i class="fas fa-hashtag"></i> No</th>
                            <th><i class="fas fa-book"></i> Mata Pelajaran</th>
                            <th>KKM</th>
                            <th><i class="fas fa-list-check"></i> Tugas</th>
                            <th><i class="fas fa-pen"></i> UTS</th>
                            <th><i class="fas fa-file-alt"></i> UAS</th>
                            <th><i class="fas fa-star"></i> Nilai Akhir</th>
                            <th><i class="fas fa-trophy"></i> Grade</th>
                            <th><i class="fas fa-check-circle"></i> Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query Join 3 Tabel (Nilai, Mapel, Siswa)
                        $queryNilai = "SELECT * FROM nilai 
                                       JOIN matapelajaran ON nilai.id_mapel = matapelajaran.id_mapel 
                                       WHERE nilai.id_siswa = '$id_siswa'";
                        $result = mysqli_query($koneksi, $queryNilai);
                        $no = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Rumus Perhitungan Nilai
                                $nilai_akhir = ($row['tugas'] * 0.3) + ($row['uts'] * 0.3) + ($row['uas'] * 0.4);
                                
                                // Logika Grade
                                if ($nilai_akhir >= 85) { $grade = 'A'; }
                                elseif ($nilai_akhir >= 75) { $grade = 'B'; }
                                elseif ($nilai_akhir >= 60) { $grade = 'C'; }
                                elseif ($nilai_akhir >= 50) { $grade = 'D'; }
                                else { $grade = 'E'; }

                                // Logika Keterangan Lulus
                                $ket = ($nilai_akhir >= $row['kkm']) ? "LULUS" : "TIDAK LULUS";
                                $warna = ($nilai_akhir >= $row['kkm']) ? "text-success" : "text-danger";
                        ?>
                        <tr class="text-center">
                            <td><?php echo $no++; ?></td>
                            <td class="text-start"><?php echo $row['nama_mapel']; ?></td>
                            <td><?php echo $row['kkm']; ?></td>
                            <td><?php echo $row['tugas']; ?></td>
                            <td><?php echo $row['uts']; ?></td>
                            <td><?php echo $row['uas']; ?></td>
                            <td><strong><?php echo number_format($nilai_akhir, 1); ?></strong></td>
                            <td><strong><?php echo $grade; ?></strong></td>
                            <td class="fw-bold <?php echo $warna; ?>"><?php echo $ket; ?></td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>Belum ada data nilai untuk siswa ini.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="button-group no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak Raport / PDF
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Home
            </a>
        </div>
    </div>
</body>
</html>