<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';

// Validasi ID parameter ada dan tidak kosong
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']); // Validasi ID harus angka

// Gunakan prepared statement untuk keamanan
$query = "DELETE FROM siswa WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);

// Jalankan query dan handle error
if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?pesan=hapus_sukses");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}

mysqli_stmt_close($stmt);
?>