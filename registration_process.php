<?php
if (!isset($_POST["submit"])) {
    header("Location: registration.php");
    exit();
}

$title = "Data Registrasi Mahasiswa";

$student_name = htmlspecialchars($student_name ?? '');
$student_number = htmlspecialchars($student_number ?? '');
$student_address = htmlspecialchars($student_address ?? '');

$student_birth_date = htmlspecialchars($student_birth_date ?? '');
$student_birth_month = htmlspecialchars($student_birth_month ?? '');
$student_birth_year = htmlspecialchars($student_birth_year ?? '');

$student_gender = $_POST['student_gender'] ?? '';
$gender_display = ($student_gender === 'Pria' || $student_gender === 'man') ? 'Pria' : 'Wanita';
$student_website = htmlspecialchars($student_website ?? '');
$student_email = htmlspecialchars($student_email ?? '');
$student_username = htmlspecialchars($student_username ?? '');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

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

        .img-preview {
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            max-width: 160px;
            height: auto;
            display: block;
            margin-top: 5px;
        }

        a.btn-back {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        a.btn-back:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2><?= $title ?></h2>

        <fieldset>
            <legend>Biodata</legend>
            <table>
                <tr>
                    <td>Nama Mahasiswa</td>
                    <td>:</td>
                    <td><?= $student_name ?></td>
                </tr>
                <tr>
                    <td>No Induk Mahasiswa (NIM)</td>
                    <td>:</td>
                    <td><?= $student_number ?></td>
                </tr>
                <tr>
                    <td>Alamat Mahasiswa</td>
                    <td>:</td>
                    <td><?= $student_address ?></td>
                </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    <td>:</td>
                    <td><?= $student_birth_date . '-' . $student_birth_month . '-' . $student_birth_year ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td><?= $gender_display ?></td>
                </tr>
                <tr>
                    <td>Foto</td>
                    <td>:</td>
                    <td>
                        <?php if (!empty($file_name)) : ?>
                            <img src="folder_upload/<?= htmlspecialchars($file_name) ?>" class="img-preview" alt="Foto Mahasiswa">
                        <?php else : ?>
                            Tidak ada foto
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>URL Website</td>
                    <td>:</td>
                    <td>
                        <a href="<?= $student_website ?>" target="_blank" style="color:#3498db;"><?= $student_website ?></a>
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset>
            <legend>Info Akun</legend>
            <table>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><?= $student_email ?></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td>:</td>
                    <td><?= $student_username ?></td>
                </tr>
            </table>
        </fieldset>

        <fieldset>
            <legend>Kemampuan Dasar</legend>
            <?php
            $skills_text = [];
            if (!empty($student_skill_html)) $skills_text[] = "HTML";
            if (!empty($student_skill_css)) $skills_text[] = "CSS";
            if (!empty($student_skill_js)) $skills_text[] = "Javascript";
            if (!empty($student_skill_php)) $skills_text[] = "PHP";
            if (!empty($student_skill_mysql)) $skills_text[] = "MySQL";
            if (!empty($student_skill_laravel)) $skills_text[] = "Laravel";
            if (!empty($student_skill_react_native)) $skills_text[] = "React Native";

            if (!empty($skills_text)) {
                echo "<p style='margin:0; font-size:14px;'>" . implode(", ", $skills_text) . "</p>";
            } else {
                echo "<p style='margin:0; font-size:14px; color:#777;'>Tidak ada kemampuan yang dipilih.</p>";
            }
            ?>
        </fieldset>

        <div style="text-align: center;">
            <a href="registration.php" class="btn-back">Kembali ke Form Registrasi</a>
        </div>
    </div>

</body>

</html>