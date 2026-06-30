<?php
// Cek dulu, jika session belum aktif, baru jalankan session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi ke database Docker MySQL kamu
$conn = mysqli_connect("mysql", "root", "root", "perpustakaan_db");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>