<?php
// Mencegah error duplikasi session_start() jika koneksi.php sudah memanggilnya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'koneksi.php';

// Proteksi halaman admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Hapus buku
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $hapus = mysqli_query($conn, "DELETE FROM buku WHERE id_buku='$id'");

    if ($hapus) {
        echo "<script>
        alert('Data berhasil dihapus');
        window.location='buku.php';
        </script>";
    } else {
        die(mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f4f6fb;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header / Topbar - Ukuran Pas Dashboard */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 110px;
            background: #030C4D;
            color: #fff;
            display: flex;
            align-items: center;
            padding: 20px 40px;
            border-bottom: 5px solid #f7a422;
            z-index: 1000;
        }

        .header img {
            width: 55px;
            margin-right: 15px;
        }

        .header h2 {
            font-size: 18px;
            margin-bottom: 3px;
            /* font-weight: bold; */
        }

        .header p {
            font-size: 12px;
            opacity: 0.9;
        }

        /* Sidebar - Ukuran & Font Pas Dashboard */
        .sidebar {
            position: fixed;
            width: 240px;
            top: 110px;
            left: 0;
            bottom: 0;
            background: #030C4D;
            z-index: 999;
            padding-top: 20px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 16px 20px;
            font-size: 16px; /* Ukuran font disesuaikan lebih proporsional seperti dashboard */
            font-weight: bold;
            transition: .3s;
        }

        .sidebar a:hover {
            background: #f7a422;
        }

        .sidebar .active {
            background: #030C4D;
            color: #fff;
        }

        .sidebar i {
            width: 35px;
            font-size: 20px;
        }

        /* Content Area */
        .content {
            margin-left: 280px;
            margin-top: 110px;
            padding: 40px;
            flex: 1;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 40px 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
        }

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .title h2 {
            font-size: 18px;
            color: #030C4D;
            border-left: 6px solid #f7a422;
            padding-left: 12px;
            font-weight: bold;
        }

        /* Tombol Tambah Buku */
        .btn {
            background: #f7a422;
            color: #030C4D;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: #e0921b;
        }

        /* Tabel - Ukuran Font & Spasi Sesuai Gambar Dashboard */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #030C4D;
            color: white;
            padding: 18px 15px;
            font-size: 18px;
            font-weight: bold;
            text-align: left;
        }

        table td {
            padding: 18px 15px;
            font-size: 16px;
            color: #111;
            border-bottom: 1px solid #eee;
        }

        table tr:hover {
            background: #f9fbfd;
        }

        /* Tombol Aksi - Menyesuaikan Gaya Dashboard */
        .btn-action {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .edit {
            background: #3498db;
            color: white;
        }

        .edit:hover {
            background: #2980b9;
        }

        .hapus {
            background: #dc3545;
            color: white;
            margin-left: 6px;
        }

        .hapus:hover {
            background: #bb2d3b;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="logo.png" alt="Logo UHO">
    <div>
        <h2>Sistem Peminjaman Buku Perpustakaan Kampus</h2>
        <p>Universitas Halu Oleo</p>
        <p>Jl. HEA Mokodompit No.1, Kendari, Sulawesi Tenggara</p>
    </div>
</div>

<div class="sidebar">
    <a href="admin.php">
        <i class="fa-solid fa-gauge"></i>
        Dashboard
    </a>
    <a href="buku.php" class="active">
        <i class="fa-solid fa-book"></i>
        Kelola Buku
    </a>
    <a href="anggota.php">
        <i class="fa-solid fa-users"></i>
        Kelola Anggota
    </a>
    <a href="logout.php">
        <i class="fa-solid fa-right-from-bracket"></i>
        Keluar
    </a>
</div>

<div class="content">
    <div class="card">
        <div class="title">
            <h2>Kelola Buku</h2>
            <a href="tambah_buku.php" class="btn">
                <i class="fa-solid fa-plus"></i>
                Tambah Buku
            </a>
        </div>

        <table>
            <tr>
                <th style="width: 70px;">No</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th style="width: 120px;">Stok</th>
                <th style="width: 240px; text-align: center;">Aksi</th>
            </tr>

            <?php
            $no = 1;
            $data = mysqli_query($conn, "SELECT * FROM buku ORDER BY id_buku DESC");
            while ($d = mysqli_fetch_assoc($data)) {
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($d['judul']); ?></td>
                <td><?= htmlspecialchars($d['pengarang']); ?></td>
                <td><?= htmlspecialchars($d['stok']); ?></td>
                <td style="text-align: center;">
                    <a class="btn-action edit" href="edit_buku.php?id=<?= $d['id_buku']; ?>">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <a class="btn-action hapus" onclick="return confirm('Yakin ingin menghapus buku ini?')" href="buku.php?hapus=<?= $d['id_buku']; ?>">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>