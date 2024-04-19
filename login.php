<?php

require_once('connection.php');

setcookie("username", "", time() - 3600, "/"); // Waktu kedaluwarsa diatur ke masa lampau

// Pastikan formulir telah dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simpan data yang dikirim dari formulir
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Lakukan query untuk mencari pengguna dengan username yang cocok
    $sql = "SELECT * FROM user WHERE username=:username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Ambil data pengguna dari hasil query
        $user = $result;
        // Periksa apakah password cocok
        if ($user['password'] == $password) {
            // Jika benar, set cookie untuk menyimpan informasi login
            setcookie("username", $username, time() + (86400 * 30), "/"); // Cookie berlaku selama 30 hari
            // Jika benar, redirect ke halaman dashboard atau halaman lainnya
            header("Location: index.php");
            exit;
        } else {
            // Jika password tidak cocok, beri pesan error
            echo "Password salah. Silakan coba lagi.";
        }
    } else {
        // Jika pengguna tidak ditemukan, beri pesan error
        echo "Username tidak ditemukan.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="asset/css/main.css"/>
</head>
<body>
<div
    class="modal"
    style="background-image: url('/asset/img/aerial-view-coconut-palm-trees-plantation-road.jpg');background-size: cover;background-position: center;background-repeat: no-repeat;backdrop-filter: opacity(0)"
>
    <form class="modal-content modal-md animate" action="" method="post">
        <div class="container">
            <header>
                <h2 class="text-center">Sistem Pendukung Keputusan Pemilihan Karyawan Panen Kelapa Sawit Terbaik Menggunakan Metode VIKOR PT. Tritunggal Sentra Buana (TSB)</h2>
            </header>
            <div class="grid-cols">
                <label class="input-label" for="username"><b>Username</b></label>
                <input
                    class="input-control"
                    type="text"
                    placeholder="Enter Username"
                    id="username"
                    name="username"
                    required
                />
            </div>
            <div class="grid-cols">
                <label for="password"><b>Password</b></label>
                <input
                    class="input-control"
                    type="password"
                    placeholder="Enter Username"
                    id="password"
                    name="password"
                    required
                />
            </div>
            <button class="btn" type="submit">Login</button>
        </div>
    </form>
</div>
</body>
</html>
