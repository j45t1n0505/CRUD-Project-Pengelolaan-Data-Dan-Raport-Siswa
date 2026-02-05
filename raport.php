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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .raport-container {
            animation: slideUp 0.6s ease-out;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            max-width: 900px;
            margin: 2rem auto;
        }

        .header-raport {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeIn 0.6s ease-out;
        }

        .header-raport h2 {
            color: #333;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .header-raport h5 {
            color: #666;
            font-weight: 600;
            margin: 0;
        }

        hr {
            border: 3px solid #667eea;
            opacity: 0.3;
            margin: 2rem 0;
            animation: fadeIn 0.8s ease-out 0.1s backwards;
        }

        .student-info {
            animation: slideIn 0.6s ease-out 0.15s backwards;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
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
            color: #555;
            width: 150px;
        }

        .student-info strong {
            color: #667eea;
            font-size: 1.1rem;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            animation: fadeIn 0.6s ease-out;
        }

        .table thead th {
            border: none;
            color: white;
            font-weight: 700;
            padding: 1.2rem 0.8rem;
            text-align: center;
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
            background-color: #f0f4ff;
            transform: scale(1.01);
            box-shadow: inset 0 0 15px rgba(102, 126, 234, 0.1);
        }

        .table tbody td {
            padding: 1rem 0.8rem;
            vertical-align: middle;
        }

        .table tbody td:first-child {
            text-align: center;
            font-weight: 700;
            color: #667eea;
        }

        .table tbody td:nth-child(n+3):nth-child(-n+6) {
            text-align: center;
            font-weight: 600;
        }

        strong {
            color: #667eea;
        }

        .text-success {
            color: #11998e !important;
            font-weight: 700;
        }

        .text-danger {
            color: #fa709a !important;
            font-weight: 700;
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
            font-weight: 600;
            border: none;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
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
            z-index: 1;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn > * {
            position: relative;
            z-index: 2;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #a8a8a8 0%, #6c6c6c 100%);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(168, 168, 168, 0.4);
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
                animation: none;
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

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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
            }
            to {
                opacity: 1;
            }
        }

        @keyframes rowFadeIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
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