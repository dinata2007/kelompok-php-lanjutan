<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/connection.php";
if (!($connection instanceof mysqli)) {
    die("Database connection is unavailable.");
}

// Cek apakah ada parameter NIM di URL
if (isset($_GET["nim"])) {
    // Ambil NIM dan amankan
    $nim = mysqli_real_escape_string($connection, $_GET["nim"]);

    // Buat Query Hapus
    $query = "DELETE FROM student WHERE nim='$nim'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $message = "Mahasiswa dengan NIM <b>$nim</b> berhasil dihapus!";
        $message = urlencode($message);
        header("Location: student_view.php?message={$message}");
        exit();
    } else {
        die("Query gagal dijalankan: " . mysqli_errno($connection) . " - " . mysqli_error($connection));
    }
} else {
    // Jika diakses langsung tanpa parameter NIM, arahkan kembali ke view
    header("Location: student_view.php");
    exit();
}
?>