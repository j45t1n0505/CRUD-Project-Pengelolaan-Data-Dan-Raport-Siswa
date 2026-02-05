<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';
header('Content-Type: application/json');

if (isset($_GET['id_siswa'])) {
    $id_siswa = $_GET['id_siswa'];
    
    // Get semua mata pelajaran dengan nilai yang sudah ada (jika ada)
    $query = "SELECT m.id_mapel, m.nama_mapel, m.kkm, 
                     COALESCE(n.tugas, '') as tugas,
                     COALESCE(n.uts, '') as uts,
                     COALESCE(n.uas, '') as uas
              FROM matapelajaran m
              LEFT JOIN nilai n ON m.id_mapel = n.id_mapel AND n.id_siswa = '$id_siswa'
              ORDER BY m.id_mapel";
    
    $result = mysqli_query($koneksi, $query);
    $data = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    echo json_encode($data);
} else {
    echo json_encode([]);
}
?>
