<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Perpustakaan</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 5px 10px; background: blue; color: white; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <h2>Daftar Buku Tersedia</h2>
    <table>
        <tr>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query = mysqli_query($conn, "SELECT * FROM buku");
        while($data = mysqli_fetch_array($query)){
            echo "<tr>
                    <td>{$data['judul']}</td>
                    <td>{$data['pengarang']}</td>
                    <td>{$data['stok']}</td>
                    <td><a class='btn' href='pinjam.php?id={$data['id_buku']}'>Pinjam</a></td>
                  </tr>";
        }
        ?>
    </table>

    <h2>Riwayat Peminjaman</h2>
    <table>
        <tr>
            <th>Nama Peminjam</th>
            <th>Buku</th>
            <th>Tanggal Pinjam</th>
        </tr>
        <?php
        $query_pinjam = mysqli_query($conn, "SELECT peminjaman.*, buku.judul FROM peminjaman JOIN buku ON peminjaman.id_buku = buku.id_buku");
        while($p = mysqli_fetch_array($query_pinjam)){
            echo "<tr>
                    <td>{$p['nama_peminjam']}</td>
                    <td>{$p['judul']}</td>
                    <td>{$p['tgl_pinjam']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>