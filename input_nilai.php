<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $id_siswa = $_POST['id_siswa'];
    $mapel_values = $_POST['mapel'] ?? [];
    $tugas_values = $_POST['tugas'] ?? [];
    $uts_values = $_POST['uts'] ?? [];
    $uas_values = $_POST['uas'] ?? [];
    
    $success_count = 0;
    $error_count = 0;

    // Loop untuk insert setiap mata pelajaran yang memiliki nilai
    foreach ($mapel_values as $index => $id_mapel) {
        $tugas = trim($tugas_values[$index] ?? '');
        $uts = trim($uts_values[$index] ?? '');
        $uas = trim($uas_values[$index] ?? '');
        
        // Hanya simpan jika minimal ada satu nilai yang diisi
        if (!empty($id_mapel) && (!empty($tugas) || !empty($uts) || !empty($uas))) {
            // Jika ada nilai yang kosong, set ke 0
            $tugas = !empty($tugas) ? $tugas : 0;
            $uts = !empty($uts) ? $uts : 0;
            $uas = !empty($uas) ? $uas : 0;

            // Check apakah data sudah ada
            $check_query = "SELECT * FROM nilai WHERE id_siswa='$id_siswa' AND id_mapel='$id_mapel'";
            $check_result = mysqli_query($koneksi, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                // Update jika sudah ada
                $query = "UPDATE nilai SET tugas='$tugas', uts='$uts', uas='$uas' WHERE id_siswa='$id_siswa' AND id_mapel='$id_mapel'";
            } else {
                // Insert jika belum ada
                $query = "INSERT INTO nilai (id_siswa, id_mapel, tugas, uts, uas) VALUES ('$id_siswa', '$id_mapel', '$tugas', '$uts', '$uas')";
            }
            
            if (mysqli_query($koneksi, $query)) {
                $success_count++;
            } else {
                $error_count++;
            }
        }
    }

    if ($success_count > 0) {
        echo "<script>alert('Nilai berhasil disimpan! ($success_count mata pelajaran)'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Tidak ada nilai yang disimpan. Silahkan isi minimal satu mata pelajaran!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Nilai Siswa</title>
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

        body {
            background: #0d0d0d;
            min-height: 100vh;
            padding: 2rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .card {
            animation: neonPop 1s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            border: 2px solid #ff003c;
            border-radius: 15px;
            box-shadow: 0 0 40px 5px #ff003c, 0 0 80px 10px #ff003c44;
            overflow: hidden;
            max-width: 600px;
            margin: 2rem auto;
            backdrop-filter: blur(10px);
            background: rgba(20, 0, 0, 0.95);
        }

        .card-header {
            background: #ff003c;
            padding: 2rem;
            border: none;
            animation: fadeIn 0.6s ease-out;
            box-shadow: 0 0 10px #ff003c99;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            color: #fff;
            text-shadow: 0 0 8px rgba(0,0,0,0.3);
        }

        .card-body {
            padding: 2.5rem;
            background: rgba(20, 0, 0, 0.95);
            animation: fadeIn 0.6s ease-out 0.2s backwards;
            border-top: 2px solid #ff003c;
        }

        .mb-3 {
            animation: formSlide 0.6s ease-out backwards;
        }

        .mb-3:nth-child(1) { animation-delay: 0.2s; }
        .mb-3:nth-child(2) { animation-delay: 0.3s; }
        .mb-3:nth-child(3) { animation-delay: 0.4s; }

        label {
            font-weight: 600;
            color: #ff003c;
            margin-bottom: 0.7rem;
            display: block;
            font-size: 1rem;
            text-shadow: 0 0 6px #ff003c99;
            letter-spacing: 0.5px;
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

        .row {
            animation: formSlide 0.6s ease-out 0.4s backwards;
        }

        .col-md-4 {
            margin-bottom: 1rem;
        }

        .mapel-section {
            animation: formSlide 0.6s ease-out 0.4s backwards;
            background: rgba(255, 0, 60, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #ff003c;
            max-height: 600px;
            overflow-y: auto;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 10px #ff003c33;
        }

        .mapel-item {
            background: rgba(20, 0, 0, 0.7);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border: 2px solid #ff003c;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 10px #ff003c33;
        }

        .mapel-item:hover {
            border-color: #fff;
            box-shadow: 0 0 20px #ff003c99;
            transform: scale(1.02);
        }

        .mapel-title {
            font-weight: 700;
            color: #ff003c;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-shadow: 0 0 6px #ff003c99;
        }

        .mapel-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
        }

        .input-group-vertical {
            display: flex;
            flex-direction: column;
        }

        .input-group-vertical label {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .input-group-vertical input {
            padding: 0.6rem 0.8rem;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
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

        .btn-success {
            background: #11998e;
            color: white;
            box-shadow: 0 0 20px #11998e99;
        }

        .btn-success:hover {
            transform: scale(1.07) translateY(-3px);
            box-shadow: 0 0 40px #11998e, 0 0 80px #11998e99;
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
            .card {
                margin: 1rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .row {
                flex-direction: column;
            }

            .col-md-4 {
                width: 100%;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-pen-fancy"></i> Form Input Nilai Siswa</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="id_siswa"><i class="fas fa-user-circle"></i> Pilih Siswa</label>
                    <select id="id_siswa" name="id_siswa" class="form-select" onchange="loadMapel(this.value)" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php
                        $siswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY nama");
                        while ($s = mysqli_fetch_assoc($siswa)) {
                            echo "<option value='".$s['id']."'>".$s['nim']." - ".$s['nama']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div id="mapel-container" style="display:none;">
                    <div class="mapel-section">
                        <h5 style="margin-bottom: 1.5rem; color: #333;"><i class="fas fa-book"></i> Daftar Mata Pelajaran</h5>
                        <div id="mapel-list"></div>
                    </div>

                    <div class="button-group">
                        <button type="submit" name="simpan" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Semua Nilai
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div id="no-selection" class="alert alert-info" style="display: block; margin-top: 2rem;">
                    <i class="fas fa-info-circle"></i> Silahkan pilih siswa terlebih dahulu untuk melihat daftar mata pelajaran
                </div>
            </form>

            <script>
                function loadMapel(id_siswa) {
                    if (!id_siswa) {
                        document.getElementById('mapel-container').style.display = 'none';
                        document.getElementById('no-selection').style.display = 'block';
                        return;
                    }

                    // Fetch data mapel menggunakan AJAX
                    fetch('get_mapel.php?id_siswa=' + id_siswa)
                        .then(response => response.json())
                        .then(data => {
                            const mapelList = document.getElementById('mapel-list');
                            mapelList.innerHTML = '';

                            if (data.length === 0) {
                                mapelList.innerHTML = '<div class="alert alert-warning">Tidak ada mata pelajaran</div>';
                            } else {
                                data.forEach((mapel, index) => {
                                    const mapelItem = document.createElement('div');
                                    mapelItem.className = 'mapel-item';
                                    mapelItem.innerHTML = `
                                        <div class="mapel-title">
                                            <i class="fas fa-book-open"></i> ${mapel.nama_mapel}
                                        </div>
                                        <input type="hidden" name="mapel[]" value="${mapel.id_mapel}">
                                        <div class="mapel-inputs">
                                            <div class="input-group-vertical">
                                                <label><i class="fas fa-list-check"></i> Tugas (30%)</label>
                                                <input type="number" name="tugas[]" class="form-control" min="0" max="100" value="${mapel.tugas || ''}">
                                            </div>
                                            <div class="input-group-vertical">
                                                <label><i class="fas fa-pen"></i> UTS (30%)</label>
                                                <input type="number" name="uts[]" class="form-control" min="0" max="100" value="${mapel.uts || ''}">
                                            </div>
                                            <div class="input-group-vertical">
                                                <label><i class="fas fa-file-alt"></i> UAS (40%)</label>
                                                <input type="number" name="uas[]" class="form-control" min="0" max="100" value="${mapel.uas || ''}">
                                            </div>
                                        </div>
                                    `;
                                    mapelList.appendChild(mapelItem);
                                });
                            }

                            document.getElementById('mapel-container').style.display = 'block';
                            document.getElementById('no-selection').style.display = 'none';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memuat data');
                        });
                }
            </script>
        </div>
    </div>
</body>
</html>