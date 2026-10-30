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

$error_message = "";

// Jika Tombol Simpan di-klik (Proses UPDATE)
if (isset($_POST["submit"])) {
    // Tangkap data dari form (sama seperti proses Add)
    $nim_lama = htmlentities(strip_tags(trim($_POST["nim_lama"])));
    $nim = htmlentities(strip_tags(trim($_POST["nim"])));
    $name = htmlentities(strip_tags(trim($_POST["name"])));
    $birth_city = htmlentities(strip_tags(trim($_POST["birth_city"])));
    $faculty = htmlentities(strip_tags(trim($_POST["faculty"])));
    $department = htmlentities(strip_tags(trim($_POST["department"])));
    $gpa = htmlentities(strip_tags(trim($_POST["gpa"])));
    $birth_date = htmlentities(strip_tags(trim($_POST["birth_date"])));
    $birth_month = htmlentities(strip_tags(trim($_POST["birth_month"])));
    $birth_year = htmlentities(strip_tags(trim($_POST["birth_year"])));

    // Validasi data kosong
    if (empty($nim))
        $error_message .= "- NIM belum diisi <br>";
    else if (!preg_match("/^[0-9]{8}$/", $nim))
        $error_message .= "- NIM harus berupa 8 digit angka <br>";
    if (empty($name))
        $error_message .= "- Nama belum diisi <br>";
    if (empty($birth_city))
        $error_message .= "- Tempat lahir belum diisi <br>";
    if (empty($department))
        $error_message .= "- Jurusan belum diisi <br>";
    if (!is_numeric($gpa) or ($gpa <= 0) or ($gpa > 4.00))
        $error_message .= "- IPK harus angka (0.01 - 4.00) <br>";

    // Jika NIM diubah, pastikan NIM baru belum dipakai orang lain
    if ($nim !== $nim_lama && $error_message === "") {
        $nim_aman = mysqli_real_escape_string($connection, $nim);
        $cek_query = "SELECT * FROM student WHERE nim='$nim_aman'";
        $cek_result = mysqli_query($connection, $cek_query);
        if (mysqli_num_rows($cek_result) >= 1) {
            $error_message .= "- NIM yang sama sudah digunakan mahasiswa lain <br>";
        }
    }

    if ($error_message === "") {
        // Amankan semua data sebelum masuk database
        $nim_lama = mysqli_real_escape_string($connection, $nim_lama);
        $nim = mysqli_real_escape_string($connection, $nim);
        $name = mysqli_real_escape_string($connection, $name);
        $birth_city = mysqli_real_escape_string($connection, $birth_city);
        $faculty = mysqli_real_escape_string($connection, $faculty);
        $department = mysqli_real_escape_string($connection, $department);
        $birth_date_full = mysqli_real_escape_string($connection, "$birth_year-$birth_month-$birth_date");
        $gpa = (float) $gpa;

        // Query UPDATE data
        $query = "UPDATE student SET 
                  nim = '$nim',
                  name = '$name',
                  birth_city = '$birth_city',
                  birth_date = '$birth_date_full',
                  faculty = '$faculty',
                  department = '$department',
                  gpa = $gpa
                  WHERE nim = '$nim_lama'";

        $result = mysqli_query($connection, $query);

        if ($result) {
            $message = "Data mahasiswa \"<b>$name</b>\" berhasil diperbarui!";
            header("Location: student_view.php?message=" . urlencode($message));
            exit();
        } else {
            die("Query gagal dijalankan: " . mysqli_errno($connection) . " - " . mysqli_error($connection));
        }
    }
}
// Jika halaman baru dibuka (Ambil data lama dari Database)
else if (isset($_GET["nim"])) {
    $nim = mysqli_real_escape_string($connection, $_GET["nim"]);
    $query = "SELECT * FROM student WHERE nim='$nim'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 0) {
        header("Location: student_view.php");
        exit();
    }

    // Ekstrak data dari database ke variabel untuk ditampilkan di form
    $data = mysqli_fetch_assoc($result);
    $nim = $data["nim"];
    $nim_lama = $data["nim"]; // Simpan NIM asli jika user berniat merubah NIM
    $name = $data["name"];
    $birth_city = $data["birth_city"];
    $faculty = $data["faculty"];
    $department = $data["department"];
    $gpa = $data["gpa"];

    // Pecah format YYYY-MM-DD kembali menjadi variabel satuan
    $birth_date = date("j", strtotime($data["birth_date"]));
    $birth_month = date("n", strtotime($data["birth_date"]));
    $birth_year = date("Y", strtotime($data["birth_date"]));
} else {
    header("Location: student_view.php");
    exit();
}

$arr_month = ["1" => "Januari", "2" => "Februari", "3" => "Maret", "4" => "April", "5" => "Mei", "6" => "Juni", "7" => "Juli", "8" => "Agustus", "9" => "September", "10" => "Oktober", "11" => "Nopember", "12" => "Desember"];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Mahasiswa</title>
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
        <h2>Edit Data Mahasiswa</h2>
        <?php
        if ($error_message !== "")
            echo "<div class='error'>$error_message</div>";
        ?>
        <form id="form_mahasiswa" action="student_edit.php" method="post">
            <fieldset>
                <legend>Edit Mahasiswa</legend>
                <!-- Input tersembunyi untuk menyimpan NIM lama sebagai rujukan UPDATE -->
                <input type="hidden" name="nim_lama" value="<?php echo htmlspecialchars($nim_lama ?? $nim); ?>">

                <p>
                    <label for="nim">NIM : </label>
                    <input type="text" name="nim" id="nim" value="<?php echo htmlspecialchars($nim); ?>"
                        placeholder="Contoh: 12345678"> (8 digit angka)
                </p>
                <p>
                    <label for="name">Nama : </label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
                </p>
                <p>
                    <label for="birth_city">Tempat Lahir : </label>
                    <input type="text" name="birth_city" id="birth_city"
                        value="<?php echo htmlspecialchars($birth_city); ?>">
                </p>
                <p>
                    <label for="birth_date">Tanggal Lahir : </label>
                    <select name="birth_date" id="birth_date">
                        <?php
                        for ($i = 1; $i <= 31; $i++) {
                            $selected = ($i == $birth_date) ? "selected" : "";
                            echo "<option value=$i $selected>" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>";
                        }
                        ?>
                    </select>
                    <select name="birth_month">
                        <?php
                        foreach ($arr_month as $key => $value) {
                            $selected = ($key == $birth_month) ? "selected" : "";
                            echo "<option value=\"$key\" $selected>$value</option>";
                        }
                        ?>
                    </select>
                    <select name="birth_year">
                        <?php
                        for ($i = 1990; $i <= 2005; $i++) {
                            $selected = ($i == $birth_year) ? "selected" : "";
                            echo "<option value=$i $selected>$i</option>";
                        }
                        ?>
                    </select>
                </p>
                <p>
                    <label for="faculty">Fakultas : </label>
                    <select name="faculty" id="faculty">
                        <option value="FTIB" <?php echo ($faculty == 'FTIB') ? "selected" : ""; ?>>FTIB</option>
                        <option value="FTEIC" <?php echo ($faculty == 'FTEIC') ? "selected" : ""; ?>>FTEIC</option>
                    </select>
                </p>
                <p>
                    <label for="department">Jurusan : </label>
                    <input type="text" name="department" id="department"
                        value="<?php echo htmlspecialchars($department); ?>">
                </p>
                <p>
                    <label for="gpa">IPK : </label>
                    <input type="text" name="gpa" id="gpa" value="<?php echo htmlspecialchars($gpa); ?>">
                </p>
            </fieldset>
            <br>
            <p>
                <input type="submit" name="submit" value="Simpan Perubahan">
            </p>
        </form>
    </div>
</body>

</html>