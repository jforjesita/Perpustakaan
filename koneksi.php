<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "perpustakaan_db");
if (!$conn) die("Koneksi gagal");
?>