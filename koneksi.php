<?php
session_start();
$conn = mysqli_connect("mysql", "root", "root", "perpustakaan_db");
if (!$conn) die("Koneksi gagal");
?>