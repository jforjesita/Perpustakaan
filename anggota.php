<?php 
include 'koneksi.php';

// Proteksi halaman: Hanya anggota yang boleh masuk
if($_SESSION['role'] != 'anggota') {
    header("Location: login.php");
    exit();
}

$id_u = $_SESSION['id'];

// Logika Ajukan Pinjam
if(isset($_GET['pinjam'])){
    $id_b = $_GET['pinjam'];
    mysqli_query($conn, "INSERT INTO peminjaman (id_user, id_buku, tgl_pinjam, status) VALUES ('$id_u', '$id_b', CURDATE(), 'menunggu')");
    header("Location: anggota.php");
}

// QUERY STATISTIK UNTUK ANGGOTA
$count_tersedia = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total FROM buku"))['total'] ?? 0;
$count_saya_pinjam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE id_user='$id_u' AND status='dipinjam'"))['total'];
$count_riwayat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE id_user='$id_u'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Anggota - Perpustakaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#F5F5F5;
    display:flex;
    flex-direction:column;
    height:100vh;
}

/* ================= HEADER ================= */
.header{
    background:#030C4D;
    color:#fff;
    padding:15px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-bottom:5px solid #F8A01B;
    box-shadow:0 2px 10px rgba(0,0,0,.15);
}

.header img{
    width:55px;
    margin-right:15px;
}

.header-text h2{
    font-size:20px;
}

.header-text p{
    font-size:13px;
    color:#dcdcdc;
}

/* ================= LAYOUT ================= */

.main-container{
    display:flex;
    flex:1;
}

/* ================= SIDEBAR ================= */

.sidebar{
    width:240px;
    background:#030C4D;
    padding-top:20px;
}

.sidebar a{
    display:flex;
    align-items:center;
    padding:16px 20px;
    text-decoration:none;
    color:#fff;
    font-weight:600;
    transition:.3s;
}

.sidebar a i{
    width:25px;
    margin-right:10px;
}

.sidebar a:hover,
.sidebar a.active{
    background:#F8A01B;
    color:#030C4D;
}

.sidebar a.keluar{
    margin-top:25px;
    color:#F8A01B;
}

.sidebar a.keluar:hover{
    background:#F8A01B;
    color:#030C4D;
}

/* ================= CONTENT ================= */

.content{
    flex:1;
    padding:25px;
    overflow-y:auto;
}

/* ================= CARD ================= */

.stats-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:25px;
}

.stat-card{
    background:#fff;
    border-radius:12px;
    display:flex;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-4px);
}

.stat-icon{
    background:#F8A01B;
    color:#030C4D;
    width:90px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.stat-icon i{
    font-size:34px;
}

.stat-info{
    padding:18px;
}

.stat-info p{
    color:#777;
    font-size:12px;
    font-weight:600;
}

.stat-info h1{
    color:#030C4D;
    font-size:32px;
}

/* ================= TABLE ================= */

.table-section{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    margin-bottom:25px;
}

.table-section h3{
    color:#030C4D;
    border-left:6px solid #F8A01B;
    padding-left:12px;
    margin-bottom:18px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#030C4D;
    color:white;
    padding:13px;
}

table td{
    padding:12px;
    border-bottom:1px solid #ddd;
}

table tr:nth-child(even){
    background:#fafafa;
}

table tr:hover{
    background:#fff5df;
}

/* ================= BUTTON ================= */

.btn-pinjam{
    background:#F8A01B;
    color:#030C4D;
    text-decoration:none;
    padding:8px 15px;
    border-radius:6px;
    font-weight:bold;
    transition:.3s;
}

.btn-pinjam:hover{
    background:#030C4D;
    color:white;
}

/* ================= STATUS ================= */

.status-badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    color:white;
    background:#030C4D;
}
    </style>
</head>
<body>

    <div class="header">
        <img src="logo.png" alt="Logo">
        <div class="header-text">
            <h2>Sistem Peminjaman Buku Perpustakaan Kampus</h2>
            <p>Halo, <b><?= $_SESSION['nama'] ?></b> | Universitas Halu Oleo</p>
        </div>
    </div>

    <div class="main-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <a href="anggota.php" class="active"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
            <a href="logout.php" class="keluar"><i class="fa fa-sign-out-alt"></i> Keluar</a>
        </div>

        <div class="content">
            <!-- BOX STATISTIK ANGGOTA -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa fa-book"></i></div>
                    <div class="stat-info">
                        <p>TOTAL BUKU TERSEDIA</p>
                        <h1><?= $count_tersedia ?></h1>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa fa-book-reader"></i></div>
                    <div class="stat-info">
                        <p>BUKU SAYA PINJAM</p>
                        <h1><?= $count_saya_pinjam ?></h1>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa fa-history"></i></div>
                    <div class="stat-info">
                        <p>TOTAL TRANSAKSI SAYA</p>
                        <h1><?= $count_riwayat ?></h1>
                    </div>
                </div>
            </div>

            <!-- DAFTAR BUKU -->
            <div class="table-section">
                <h3>Daftar Buku Perpustakaan</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $buku = mysqli_query($conn, "SELECT * FROM buku");
                        while($b = mysqli_fetch_assoc($buku)){ ?>
                        <tr>
                            <td><?= $b['judul'] ?></td>
                            <td><?= $b['pengarang'] ?></td>
                            <td><?= $b['stok'] ?></td>
                            <td>
                                <?php if($b['stok'] > 0) { ?>
                                    <a href="?pinjam=<?= $b['id_buku'] ?>" class="btn-pinjam">Ajukan Pinjam</a>
                                <?php } else { echo "Stok Habis"; } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- STATUS PEMINJAMAN SAYA -->
            <div class="table-section">
                <h3>Status Peminjaman Saya</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $pinjam = mysqli_query($conn, "SELECT p.*, b.judul FROM peminjaman p JOIN buku b ON p.id_buku=b.id_buku WHERE id_user='$id_u' ORDER BY p.id_pinjam DESC");
                        while($p = mysqli_fetch_assoc($pinjam)){ ?>
                        <tr>
                            <td><?= $p['judul'] ?></td>
                            <td><?= $p['tgl_pinjam'] ?></td>
                            <td><span class="status-badge"><?= $p['status'] ?></span></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>