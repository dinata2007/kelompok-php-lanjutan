<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

include "connection.php";

if (!isset($connection)) {
    $connection = null;
}

if (isset($_GET["message"])) {
    $message = $_GET["message"];
}

$query = "SELECT * FROM student ORDER BY nim ASC";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div id="header">
            <h1 id="logo">Data Mahasiswa</h1>
        </div>
        <hr>
        <nav>
            <ul>
                <li><a href="student_view.php">Tampil</a></li>
                <li><a href="student_add.php">Tambah</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <?php
        if (isset($message)) {
            echo "<div class='pesan'>$message</div>";
        }
        ?>
        <table border="1">
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Fakultas</th>
                <th>Jurusan</th>
                <th>IPK</th>
                <th>Aksi</th> <!-- Tambahan Kolom Aksi -->
            </tr>
            <?php
            if (!isset($connection) || !is_object($connection) || !($connection instanceof mysqli)) {
                die("Koneksi database tidak tersedia.");
            }

            $result = mysqli_query($connection, $query);
            if (!$result) {
                die("Query Error: " . mysqli_errno($connection) . " - " . mysqli_error($connection));
            }

            while ($data = mysqli_fetch_assoc($result)) {
                $birth_date = strtotime($data["birth_date"] ?? "");
                $formatted_date = $birth_date ? date("d-m-Y", $birth_date) : "-";

                echo "<tr>";
                echo "<td>" . htmlspecialchars($data["nim"], ENT_QUOTES, "UTF-8") . "</td>";
                echo "<td>" . htmlspecialchars($data["name"], ENT_QUOTES, "UTF-8") . "</td>";
                echo "<td>" . htmlspecialchars($data["birth_city"], ENT_QUOTES, "UTF-8") . "</td>";
                echo "<td>$formatted_date</td>";
                echo "<td>" . htmlspecialchars($data["faculty"], ENT_QUOTES, "UTF-8") . "</td>";
                echo "<td>" . htmlspecialchars($data["department"], ENT_QUOTES, "UTF-8") . "</td>";
                echo "<td>" . htmlspecialchars($data["gpa"], ENT_QUOTES, "UTF-8") . "</td>";
                // Tambahan Link Edit dan Hapus
                echo "<td>";
                echo "<a href='student_edit.php?nim={$data['nim']}'>Edit</a> | ";
                echo "<a href='student_delete.php?nim={$data['nim']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus mahasiswa ini?\")'>Hapus</a>";
                echo "</td>";
                echo "</tr>";
            }

            mysqli_free_result($result);
            mysqli_close($connection);
            ?>
        </table>
    </div>
</body>

</html>