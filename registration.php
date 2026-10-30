<?php
$title = "Form Registrasi Mahasiswa";
$arr_month = [
    "1" => "Januari",
    "2" => "Februari",
    "3" => "Maret",
    "4" => "April",
    "5" => "Mei",
    "6" => "Juni",
    "7" => "Juli",
    "8" => "Agustus",
    "9" => "September",
    "10" => "Oktober",
    "11" => "November",
    "12" => "Desember",
];
$error_message = "";

if (isset($_POST["submit"])) {
    $student_name = htmlentities(strip_tags(trim($_POST["student_name"])));
    $student_number = htmlentities(strip_tags(trim($_POST["student_number"])));
    $student_address = htmlentities(strip_tags(trim($_POST["student_address"])));
    $student_birth_date = htmlentities(strip_tags(trim($_POST["student_birth_date"])));
    $student_birth_month = htmlentities(strip_tags(trim($_POST["student_birth_month"])));
    $student_birth_year = htmlentities(strip_tags(trim($_POST["student_birth_year"])));
    $student_gender = htmlentities(strip_tags(trim($_POST["student_gender"] ?? '')));
    $student_website = htmlentities(strip_tags(trim($_POST["student_website"])));
    $student_email = htmlentities(strip_tags(trim($_POST["student_email"])));
    $student_username = htmlentities(strip_tags(trim($_POST["student_username"])));
    $student_password = htmlentities(strip_tags(trim($_POST["student_password"])));
    $student_password_confirmation = htmlentities(strip_tags(trim($_POST["student_password_confirmation"])));

    if (empty($student_name))
        $error_message .= "- Nama Mahasiswa belum diisi <br>";
    if (empty($student_number))
        $error_message .= "- No Induk Mahasiswa (NIM) belum diisi <br>";
    if (empty($student_address))
        $error_message .= "- Alamat Mahasiswa belum diisi <br>";
    if (empty($student_website))
        $error_message .= "- URL Website belum diisi <br>";

    // Validasi Upload Foto
    $upload_error = $_FILES["student_photo"]["error"] ?? 4;
    $file_name = "";
    if ($upload_error !== 0) {
        $arr_upload_error = [
            1 => '- Ukuran file foto melewati batas maksimal <br>',
            2 => '- Ukuran file foto melewati batas maksimal 1MB <br>',
            3 => '- File foto hanya ter-upload sebagian <br>',
            4 => '- Foto belum dipilih <br>',
            6 => '- Server Error (Upload Foto) <br>',
            7 => '- Server Error (Upload Foto) <br>',
            8 => '- Server Error (Upload Foto) <br>',
        ];
        $error_message .= $arr_upload_error[$upload_error] ?? "- Terjadi kesalahan upload <br>";
    } else {
        $file_name = $_FILES["student_photo"]["name"];
        $file_size = $_FILES["student_photo"]["size"];

        if ($file_size > 1048576) {
            $error_message .= "- Ukuran file foto melebihi 1MB <br>";
        }

        $check = getimagesize($_FILES["student_photo"]["tmp_name"]);
        if ($check === false) {
            $error_message .= "- Mohon upload file gambar valid (gif, png, atau jpg) <br>";
        }
    }

    if (empty($student_email))
        $error_message .= "- Email belum diisi <br>";
    if (empty($student_username))
        $error_message .= "- Username belum diisi <br>";
    if (empty($student_password))
        $error_message .= "- Password belum diisi <br>";
    if (empty($student_password_confirmation))
        $error_message .= "- Konfirmasi Password belum diisi <br>";

    $checked_man = ($student_gender === 'man') ? "checked" : "";
    $checked_woman = ($student_gender === 'woman') ? "checked" : "";

    $student_skill_html = isset($_POST["student_skill_html"]) ? "checked" : "";
    $student_skill_css = isset($_POST["student_skill_css"]) ? "checked" : "";
    $student_skill_js = isset($_POST["student_skill_js"]) ? "checked" : "";
    $student_skill_php = isset($_POST["student_skill_php"]) ? "checked" : "";
    $student_skill_mysql = isset($_POST["student_skill_mysql"]) ? "checked" : "";
    $student_skill_laravel = isset($_POST["student_skill_laravel"]) ? "checked" : "";
    $student_skill_react_native = isset($_POST["student_skill_react_native"]) ? "checked" : "";

    if ($error_message === "") {
        $folder_name = "folder_upload";
        if (!file_exists($folder_name)) {
            mkdir($folder_name, 0777, true);
        }
        $tmp = $_FILES["student_photo"]["tmp_name"];
        move_uploaded_file($tmp, "$folder_name/$file_name");

        include("registration_process.php");
        die();
    }
} else {
    $student_name = "";
    $student_number = "";
    $student_address = "";
    $student_birth_date = 1;
    $student_birth_month = "1";
    $student_birth_year = "1990";
    $checked_man = "checked";
    $checked_woman = "";
    $student_website = "";
    $student_email = "";
    $student_username = "";
    $student_password = "";
    $student_password_confirmation = "";
    $student_skill_html = "";
    $student_skill_css = "";
    $student_skill_js = "";
    $student_skill_php = "";
    $student_skill_mysql = "";
    $student_skill_laravel = "";
    $student_skill_react_native = "";
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- CSS DITULIS LANGSUNG DI SINI AGAR PASTI TERBACA -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 30px 15px;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .error {
            background-color: #ffecec;
            color: #d9534f;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            font-size: 14px;
        }

        fieldset {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 15px 20px;
            margin-bottom: 20px;
            background-color: #fafafa;
        }

        legend {
            font-weight: bold;
            color: #3498db;
            padding: 0 8px;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px 4px;
            font-size: 14px;
        }

        td:first-child {
            width: 35%;
            font-weight: 600;
            color: #444;
        }

        td:nth-child(2) {
            width: 3%;
        }

        input[type="text"],
        input[type="email"],
        input[type="url"],
        input[type="password"],
        textarea,
        select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="url"]:focus,
        input[type="password"]:focus,
        textarea:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        select {
            width: auto;
            display: inline-block;
            margin-right: 4px;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 15px;
        }

        .skills-container label {
            font-size: 14px;
            cursor: pointer;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        input[type="submit"],
        input[type="reset"] {
            padding: 9px 20px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        input[type="submit"] {
            background-color: #2ecc71;
            color: white;
        }

        input[type="submit"]:hover {
            background-color: #27ae60;
        }

        input[type="reset"] {
            background-color: #e74c3c;
            color: white;
        }

        input[type="reset"]:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><?= $title ?></h2>
        <?php
        if ($error_message !== "")
            echo "<div class='error'>$error_message</div>";
        ?>
        <form action="registration.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Biodata</legend>
                <table>
                    <tr>
                        <td>Nama Mahasiswa*</td>
                        <td>:</td>
                        <td><input type="text" name="student_name" value="<?= $student_name ?>" placeholder="Nama Anda">
                        </td>
                    </tr>
                    <tr>
                        <td>No Induk Mahasiswa (NIM)*</td>
                        <td>:</td>
                        <td><input type="text" name="student_number" value="<?= $student_number ?>"
                                placeholder="NIM Anda"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">Alamat Mahasiswa*</td>
                        <td style="vertical-align: top;">:</td>
                        <td><textarea name="student_address" cols="30" rows="4"
                                placeholder="Alamat Anda"><?= $student_address ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir*</td>
                        <td>:</td>
                        <td>
                            <select name="student_birth_date">
                                <?php
                                for ($i = 1; $i <= 31; $i++) {
                                    $selected = ($i == $student_birth_date) ? "selected" : "";
                                    echo "<option value='$i' $selected>" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>";
                                }
                                ?>
                            </select>
                            <select name="student_birth_month">
                                <?php
                                foreach ($arr_month as $key => $value) {
                                    $selected = ($key == $student_birth_month) ? "selected" : "";
                                    echo "<option value='$key' $selected>$value</option>";
                                }
                                ?>
                            </select>
                            <select name="student_birth_year">
                                <?php
                                for ($i = 1990; $i <= 2005; $i++) {
                                    $selected = ($i == $student_birth_year) ? "selected" : "";
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin*</td>
                        <td>:</td>
                        <td>
                            <label><input type="radio" name="student_gender" value="man" <?= $checked_man ?>>
                                Pria</label>
                            &nbsp;
                            <label><input type="radio" name="student_gender" value="woman" <?= $checked_woman ?>>
                                Wanita</label>
                        </td>
                    </tr>
                    <tr>
                        <td>Upload Foto*</td>
                        <td>:</td>
                        <td>
                            <input type="file" name="student_photo" id="file_upload" accept="image/*">
                            <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                        </td>
                    </tr>
                    <tr>
                        <td>URL Website*</td>
                        <td>:</td>
                        <td><input type="url" name="student_website" value="<?= $student_website ?>"
                                placeholder="https://contoh.com"></td>
                    </tr>
                </table>
            </fieldset>

            <fieldset>
                <legend>Info Akun</legend>
                <table>
                    <tr>
                        <td>Email*</td>
                        <td>:</td>
                        <td><input type="email" name="student_email" value="<?= $student_email ?>"
                                placeholder="Email Anda"></td>
                    </tr>
                    <tr>
                        <td>Username*</td>
                        <td>:</td>
                        <td><input type="text" name="student_username" value="<?= $student_username ?>"
                                placeholder="Username Anda"></td>
                    </tr>
                    <tr>
                        <td>Password*</td>
                        <td>:</td>
                        <td><input type="password" name="student_password" value="<?= $student_password ?>"
                                placeholder="Password Anda"></td>
                    </tr>
                    <tr>
                        <td>Konfirmasi Password*</td>
                        <td>:</td>
                        <td><input type="password" name="student_password_confirmation"
                                value="<?= $student_password_confirmation ?>" placeholder="Ulangi Password"></td>
                    </tr>
                </table>
            </fieldset>

            <fieldset>
                <legend>Kemampuan Dasar</legend>
                <div class="skills-container">
                    <label><input type="checkbox" name="student_skill_html" value="html" <?= $student_skill_html ?>>
                        HTML</label>
                    <label><input type="checkbox" name="student_skill_css" value="css" <?= $student_skill_css ?>>
                        CSS</label>
                    <label><input type="checkbox" name="student_skill_js" value="javascript" <?= $student_skill_js ?>>
                        Javascript</label>
                    <label><input type="checkbox" name="student_skill_php" value="php" <?= $student_skill_php ?>>
                        PHP</label>
                    <label><input type="checkbox" name="student_skill_mysql" value="mysql" <?= $student_skill_mysql ?>>
                        MySQL</label>
                    <label><input type="checkbox" name="student_skill_laravel" value="laravel" <?= $student_skill_laravel ?>> Laravel</label>
                    <label><input type="checkbox" name="student_skill_react_native" value="react_native"
                            <?= $student_skill_react_native ?>> React Native</label>
                </div>
            </fieldset>

            <div class="button-group">
                <input type="reset" value="Reset">
                <input type="submit" name="submit" value="Simpan">
            </div>
        </form>
    </div>
</body>

</html>