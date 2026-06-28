<?php 
include 'koneksi.php';
$id_buku = $_GET['id'];

if(isset($_POST['submit'])){
    $nama = $_POST['nama_peminjam'];
    $tgl = date('Y-m-d');

    // Simpan ke tabel peminjaman
    mysqli_query($conn, "INSERT INTO peminjaman (nama_peminjam, id_buku, tgl_pinjam) VALUES ('$nama', '$id_buku', '$tgl')");
    
    // Kurangi stok buku
    mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'");

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Pinjam Buku</title>
</head>
<body>
    <h2>Form Peminjaman Buku</h2>
    <form method="POST">
        <label>Nama Peminjam:</label><br>
        <input type="text" name="nama_peminjam" required><br><br>
        <button type="submit" name="submit">Konfirmasi Pinjam</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>