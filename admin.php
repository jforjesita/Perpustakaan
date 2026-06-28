<?php 
include 'koneksi.php';

// Proteksi halaman: Hanya admin yang boleh masuk
if($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Logika 1: Menyetujui Peminjaman (Status 'menunggu' -> 'dipinjam')
if(isset($_GET['setujui'])){
    $id_p = $_GET['setujui'];
    $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_buku FROM peminjaman WHERE id_pinjam='$id_p'"));
    mysqli_query($conn, "UPDATE peminjaman SET status='dipinjam' WHERE id_pinjam='$id_p'");
    mysqli_query($conn, "UPDATE buku SET stok=stok-1 WHERE id_buku='".$p['id_buku']."'");
    header("Location: admin.php");
}

// Logika 2: Proses Pengembalian (Status 'dipinjam' -> 'kembali')
if(isset($_GET['kembali'])){
    $id_p = $_GET['kembali'];
    $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_buku FROM peminjaman WHERE id_pinjam='$id_p'"));
    mysqli_query($conn, "UPDATE peminjaman SET status='kembali' WHERE id_pinjam='$id_p'");
    mysqli_query($conn, "UPDATE buku SET stok=stok+1 WHERE id_buku='".$p['id_buku']."'");
    header("Location: admin.php");
}

// QUERY UNTUK STATISTIK DASHBOARD
$count_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total FROM buku"))['total'] ?? 0;
$count_pinjam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'"))['total'];
$count_anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='anggota'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Perpustakaan</title>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',Tahoma,sans-serif;
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
    color:white;
    padding:15px 25px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-bottom:5px solid #F8A01B;
    box-shadow:0 3px 10px rgba(0,0,0,.15);
}

.header img{
    width:55px;
    margin-right:15px;
}

.header-text h2{
    font-size:20px;
    margin-bottom:3px;
}

.header-text p{
    font-size:12px;
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
    box-shadow:2px 0 10px rgba(0,0,0,.08);
}

.sidebar a{
    display:flex;
    align-items:center;
    padding:16px 20px;
    text-decoration:none;
    color:white;
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
    margin-top:20px;
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

/* ================= CARD STATISTIK ================= */

.stats-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:25px;
}

.stat-card{
    background:white;
    border-radius:12px;
    overflow:hidden;
    display:flex;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-5px);
}

.stat-icon{
    width:90px;
    background:#F8A01B;
    color:#030C4D;
    display:flex;
    align-items:center;
    justify-content:center;
}

.stat-icon i{
    font-size:34px;
}

.stat-info{
    padding:18px;
}

.stat-info p{
    font-size:12px;
    color:#6b7280;
    font-weight:600;
}

.stat-info h1{
    font-size:32px;
    color:#030C4D;
}

/* ================= TABLE SECTION ================= */

.table-section{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.table-section h3{
    color:#030C4D;
    margin-bottom:20px;
    border-left:6px solid #F8A01B;
    padding-left:12px;
}

/* ================= TABLE ================= */

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#030C4D;
    color:white;
    padding:14px;
    text-align:left;
}

table td{
    padding:12px;
    border-bottom:1px solid #e5e7eb;
}

table tr:nth-child(even){
    background:#fafafa;
}

table tr:hover{
    background:#fff6e5;
}

/* ================= STATUS ================= */

.status-menunggu{
    background:#F8A01B;
    color:#030C4D;
    padding:5px 12px;
    border-radius:20px;
    font-weight:bold;
    font-size:12px;
}

.status-dipinjam{
    background:#030C4D;
    color:white;
    padding:5px 12px;
    border-radius:20px;
    font-weight:bold;
    font-size:12px;
}

.status-kembali{
    background:#2ecc71;
    color:white;
    padding:5px 12px;
    border-radius:20px;
    font-weight:bold;
    font-size:12px;
}

/* ================= BUTTON ================= */

.btn-action{
    text-decoration:none;
    font-weight:bold;
    padding:8px 14px;
    border-radius:6px;
    transition:.3s;
    font-size:12px;
}

.btn-setujui{
    background:#F8A01B;
    color:#030C4D;
}

.btn-setujui:hover{
    background:#030C4D;
    color:white;
}

.btn-kembali{
    background:#030C4D;
    color:white;
}

.btn-kembali:hover{
    background:#F8A01B;
    color:#030C4D;
}
    </style>
</head>
<body>

    <div class="header">
        <img src="logo.png" alt="Logo">
        <div class="header-text">
            <h2>Sistem Peminjaman Buku Perpustakaan Kampus</h2>
            <p>Jl. HEA Mokodompit No.1, Kendari, Sulawesi Tenggara</p>
            <p>Terakreditasi "A" | Universitas Halu Oleo</p>
        </div>
    </div>

    <div class="main-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <a href="admin.php" class="active"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
            <a href="logout.php" class="keluar"><i class="fa fa-sign-out-alt"></i> Keluar</a>
        </div>

        <!-- CONTENT AREA -->
        <div class="content">
            <!-- BOX STATISTIK -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa fa-book"></i></div>
                    <div class="stat-info">
                        <p>TOTAL STOK BUKU</p>
                        <h1><?= $count_buku ?></h1>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa fa-exchange-alt"></i></div>
                    <div class="stat-info">
                        <p>BUKU SEDANG DIPINJAM</p>
                        <h1><?= $count_pinjam ?></h1>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa fa-users"></i></div>
                    <div class="stat-info">
                        <p>TOTAL ANGGOTA</p>
                        <h1><?= $count_anggota ?></h1>
                    </div>
                </div>
            </div>

            <!-- TABEL KELOLA PEMINJAMAN -->
            <div class="table-section">
                <h3>Kelola Peminjaman Buku</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Anggota</th>
                            <th>Judul Buku</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $res = mysqli_query($conn, "SELECT p.*, u.nama, b.judul FROM peminjaman p 
                                                   JOIN users u ON p.id_user=u.id_user 
                                                   JOIN buku b ON p.id_buku=b.id_buku 
                                                   ORDER BY p.id_pinjam DESC");
                        while($row = mysqli_fetch_assoc($res)) { ?>
                        <tr>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['judul'] ?></td>
                            <td>
                                <b><?= strtoupper($row['status']) ?></b>
                            </td>
                            <td>
                                <?php if($row['status'] == 'menunggu') : ?>
                                    <a href="?setujui=<?= $row['id_pinjam'] ?>" class="btn-action" style="background:#2ecc71; color:white;">Setujui Pinjam</a>
                                <?php elseif($row['status'] == 'dipinjam') : ?>
                                    <a href="?kembali=<?= $row['id_pinjam'] ?>" class="btn-action" style="background:#3498db; color:white;">Proses Kembali</a>
                                <?php else : ?>
                                    <span style="color: #95a5a6;">Transaksi Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>