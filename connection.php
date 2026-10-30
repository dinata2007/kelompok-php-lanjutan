<?php
$dbhost = "127.0.0.1"; // Lebih aman menggunakan 127.0.0.1 daripada localhost di FlyEnv
$dbuser = "root";      // Sesuaikan dengan Username di FlyEnv
$dbpass = "root";          // Sesuaikan dengan Password di FlyEnv (jika di FlyEnv ada isinya)
$dbname = "my_campus";
$dbport = 3306;        // Sesuaikan dengan Port yang Anda lihat di FlyEnv

// Fungsi koneksi ditambahkan parameter port
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname, $dbport);

if (!$connection) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>