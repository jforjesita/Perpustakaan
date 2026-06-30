<?php 
// Mencegah error duplikasi session_start() jika koneksi.php sudah memanggilnya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'koneksi.php';

// Proteksi halaman: Hanya admin yang boleh masuk
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Simpan data
if(isset($_POST['simpan'])){
    $judul      = mysqli_real_escape_string($conn, $_POST['judul']);
    $pengarang  = mysqli_real_escape_string($conn, $_POST['pengarang']);
    $stok       = mysqli_real_escape_string($conn, $_POST['stok']);

    $query = mysqli_query($conn, "INSERT INTO buku(judul, pengarang, stok) VALUES('$judul', '$pengarang', '$stok')");

    if($query){
        echo "<script>
                alert('Buku berhasil ditambahkan');
                window.location='buku.php';
              </script>";
    }else{
        echo "<script>
                alert('Gagal menambahkan buku');
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku - Perpustakaan</title>
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
            overflow: hidden;
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
            height: 95px;
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

        /* ================= LAYOUT CONTAINER ================= */
        .main-container{
            display:flex;
            flex:1;
            height: calc(100vh - 95px);
        }

        /* ================= SIDEBAR ================= */
        .sidebar{
            width:240px;
            background:#030C4D;
            padding-top:20px;
            box-shadow:2px 0 10px rgba(0,0,0,.08);
            flex-shrink: 0;
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

        /* Tetap menyalakan menu Kelola Buku sebagai posisi aktif */
        .sidebar a:hover,
        .sidebar a.active {
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

        /* ================= CONTENT AREA ================= */
        .content{
            flex:1;
            padding:25px;
            overflow-y:auto;
        }

        /* ================= FORM CARD SECTION ================= */
        .form-section{
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 5px 15px rgba(0,0,0,.08);
            max-width: 700px; /* Batasi lebar form agar elegan dan tidak terlalu mulur kesamping */
            margin: 0 auto;  /* Posisi form pas di tengah content */
        }

        .form-section h3{
            color:#030C4D;
            margin-bottom:25px;
            border-left:6px solid #F8A01B;
            padding-left:12px;
            font-size: 20px;
        }

        /* ================= STYLING FORM INPUT ================= */
        .form-group {
            margin-bottom: 20px;
        }

        label{
            display:block;
            margin-bottom: 8px;
            font-weight:600;
            color: #4b5563;
            font-size: 14px;
        }

        input{
            width:100%;
            padding:12px 15px;
            border:1px solid #d1d5db;
            border-radius:6px;
            font-size: 15px;
            transition: .2s;
            outline: none;
            color: #333;
        }

        input:focus {
            border-color: #030C4D;
            box-shadow: 0 0 0 3px rgba(3, 12, 77, 0.1);
        }

        /* ================= BUTTONS ================= */
        .btn-container {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }

        button{
            background:#F8A01B;
            color:#030C4D;
            border:none;
            padding:12px 24px;
            border-radius:6px;
            cursor:pointer;
            font-weight:bold;
            font-size:14px;
            transition:.3s;
            display:inline-flex;
            align-items:center;
            gap:8px;
        }

        button:hover{
            background:#030C4D;
            color:white;
        }

        .btn-kembali{
            text-decoration:none;
            color:white;
            background:#6b7280;
            padding:12px 22px;
            border-radius:6px;
            font-weight:bold;
            font-size:14px;
            transition:.3s;
            display:inline-flex;
            align-items:center;
            gap:8px;
        }

        .btn-kembali:hover{
            background:#4b5563;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="logo.png" alt="Logo">
        <div class="header-text">
            <h2>Sistem Peminjaman Buku Perpustakaan Kampus</h2>
            <p>Universitas Halu Oleo</p>
            <p>Jl. HEA Mokodompit No.1, Kendari, Sulawesi Tenggara</p>
        </div>
    </div>

    <div class="main-container">
        
        <div class="sidebar">
            <a href="admin.php">
                <i class="fa fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="buku.php" class="active">
                <i class="fa fa-book"></i> Kelola Buku
            </a>
            <a href="anggota.php">
                <i class="fa fa-users"></i> Kelola Anggota
            </a>
            <a href="logout.php" class="keluar">
                <i class="fa fa-sign-out-alt"></i> Keluar
            </a>
        </div>

        <div class="content">
            <div class="form-section">
                <h3>Tambah Buku Baru</h3>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input type="text" name="judul" required placeholder="Masukkan judul lengkap buku...">
                    </div>

                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang" required placeholder="Nama penulis/pengarang...">
                    </div>

                    <div class="form-group">
                        <label>Stok Buku</label>
                        <input type="number" name="stok" min="1" required placeholder="Jumlah stok...">
                    </div>

                    <div class="btn-container">
                        <button type="submit" name="simpan">
                            <i class="fa fa-save"></i> Simpan Buku
                        </button>
                        
                        <a href="buku.php" class="btn-kembali">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>