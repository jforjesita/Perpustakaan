<?php 
include 'koneksi.php';

// Cek jika sudah login, alihkan ke dashboard masing-masing
if(isset($_SESSION['role'])){
    header("Location: " . ($_SESSION['role'] == 'admin' ? "admin.php" : "anggota.php"));
}

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    
    $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    
    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);
        $_SESSION['id'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama'];
        header("Location: " . ($user['role'] == 'admin' ? "admin.php" : "anggota.php"));
    } else { 
        $error = "Username atau Password salah!"; 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Perpustakaan</title>
    <!-- Link Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Ganti background-color menjadi background-image */
            background-image: url('backround.jpeg'); 
            
            /* Tambahkan ini agar gambar menutupi seluruh layar dengan rapi */
            background-size: cover; 
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        /* Letakkan di sini, di bawah kurung kurawal body */
        body::before {
            content: "";
            position: absolute;
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Hitam transparan 50% */
            z-index: -1; /* Agar berada di belakang tulisan & kotak login */
        }

        .title {
            color: #f5f9f7; /* Warna hijau judul */
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .login-card {
            /* Menggunakan rgba: 255,255,255 adalah warna putih, 0.2 adalah tingkat transparansi (20%) */
            background: rgba(255, 255, 255, 0.15); 
            
            /* Menambahkan border agar bentuk kotak tetap terlihat jelas */
            border: 2px solid rgba(255, 255, 255, 0.3);
            
            /* Efek Blur (Opsional tapi sangat disarankan) agar background di belakang kotak terlihat buram
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px); */

            padding: 40px;
            border-radius: 10px; /* Membuat sudut sedikit membulat agar lebih modern */
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3); /* Shadow lebih tebal agar kotak 'menonjol' */
            width: 350px;
            text-align: center;
        }

        /* Tambahan: Agar teks di dalam kotak tetap mudah dibaca */
        .login-card h3, .login-card label {
            color: #f5f9f7;
            /* text-shadow: 1px 1px 2px rgba(248, 240, 240, 0.5); */
        }

        .login-card img {
            width: 150px; /* Ukuran gambar buku */
            margin-bottom: 10px;
        }

        .login-card h3 {
            color: #666;
            font-weight: normal;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 40px 12px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            outline: none;
        }

        .input-group i {
            position: absolute;
            right: 15px;
            top: 13px;
            color: #888;
        }

        .btn-masuk {
            background-color: #00a65a; /* Warna hijau tombol */
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            float: right;
            transition: 0.3s;
        }

        .btn-masuk:hover {
            background-color: #008d4c;
        }

        .error-msg {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* Bersihkan float */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="title">Sistem Peminjaman Buku Perpustakaan Kampus</div>

    <div class="login-card">
        <!-- Gambar Buku (Ganti URL ini dengan gambar Anda jika ada) -->
        <img src="logo.png" alt="Logo Buku">
        
        <h3>Login System</h3>

        <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

        <form method="POST" class="clearfix">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
                <i class="fa fa-user"></i>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
                <i class="fa fa-lock"></i>
            </div>

            <button type="submit" name="login" class="btn-masuk">Masuk</button>
        </form>
    </div>

</body>
</html>