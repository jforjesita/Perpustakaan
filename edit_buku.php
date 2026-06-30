<?php 
// PANGGILAN KONEKSI (session_start() sudah diatur aman di dalam koneksi.php agar tidak bentrok)
include 'koneksi.php';

// Proteksi admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil ID Buku
if (!isset($_GET['id'])) {
    header("Location: buku.php");
    exit();
}

$id = intval($_GET['id']);

$data = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku='$id'");
$buku = mysqli_fetch_assoc($data);

if (!$buku) {
    echo "<script>
            alert('Data buku tidak ditemukan!');
            window.location='buku.php';
          </script>";
    exit();
}

// Update Data
if (isset($_POST['update'])) {
    $judul      = mysqli_real_escape_string($conn, $_POST['judul']);
    $pengarang  = mysqli_real_escape_string($conn, $_POST['pengarang']);
    $stok       = mysqli_real_escape_string($conn, $_POST['stok']);

    $update = mysqli_query($conn, "
        UPDATE buku
        SET
            judul='$judul',
            pengarang='$pengarang',
            stok='$stok'
        WHERE id_buku='$id'
    ");

    if ($update) {
        echo "<script>
                alert('Data buku berhasil diupdate');
                window.location='buku.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengupdate data');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku - Perpustakaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        body {
            background: #F5F5F5;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* ================= HEADER ================= */
        .header {
            background: #030C4D;
            color: white;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 5px solid #F8A01B;
            box-shadow: 0 3px 10px rgba(0,0,0,.15);
            height: 95px;
        }

        .header img {
            width: 55px;
            margin-right: 15px;
        }

        .header-text h2 {
            font-size: 20px;
            margin-bottom: 3px;
        }

        .header-text p {
            font-size: 12px;
            color: #dcdcdc;
        }

        /* ================= LAYOUT CONTAINER ================= */
        .main-container {
            display: flex;
            flex: 1;
            height: calc(100vh - 95px);
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 240px;
            background: #030C4D;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,.08);
            flex-shrink: 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            transition: .3s;
        }

        .sidebar a i {
            width: 25px;
            margin-right: 10px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #F8A01B;
            color: #030C4D;
        }

        .sidebar a.keluar {
            margin-top: 20px;
            color: #F8A01B;
        }

        .sidebar a.keluar:hover {
            background: #F8A01B;
            color: #030C4D;
        }

        /* ================= CONTENT AREA ================= */
        .content {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
        }

        /* ================= FORM CARD SECTION ================= */
        .form-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,.08);
            max-width: 700px;
            margin: 0 auto;
        }

        .form-section h3 {
            color: #030C4D;
            margin-bottom: 25px;
            border-left: 6px solid #F8A01B;
            padding-left: 12px;
            font-size: 20px;
        }

        /* ================= INPUT STYLING ================= */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4b5563;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
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

        button {
            background: #F8A01B;
            color: #030C4D;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: .3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        button:hover {
            background: #030C4D;
            color: white;
        }

        .btn-kembali {
            text-decoration: none;
            color: white;
            background: #6b7280;
            padding: 12px 22px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            transition: .3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-kembali:hover {
            background: #4b5563;
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
                <h3>Edit Data Buku</h3>

                <form method="POST">
                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input type="text" name="judul" value="<?= htmlspecialchars($buku['judul']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang" value="<?= htmlspecialchars($buku['pengarang']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Stok Buku</label>
                        <input type="number" name="stok" value="<?= htmlspecialchars($buku['stok']); ?>" min="1" required>
                    </div>

                    <div class="btn-container">
                        <button type="submit" name="update">
                            <i class="fa fa-save"></i> Update Data
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