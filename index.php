<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_reparasi_elektronik";
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM perbaikan WHERE id_perbaikan = $id");
    header("Location: index.php");
    exit;
}

$edit_mode = false;
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id_edit = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM perbaikan WHERE id_perbaikan = $id_edit");
    $edit_data = $edit_result->fetch_assoc();
}

if (isset($_POST['update_perbaikan'])) {
    $id = $_POST['id'];
    $alat_elektronik = $_POST['alat_elektronik'];
    $merek = $_POST['merek'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $no_hp = $_POST['no_hp'];
    $tgl_masuk = $_POST['tgl_masuk'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $status = $_POST['status'];
    $biaya = $_POST['biaya'];

    if ($alat_elektronik && $merek && $nama_pelanggan && $no_hp && $tgl_masuk && $tgl_selesai && $status && $biaya !== "") {
        $conn->query("UPDATE perbaikan SET 
            alat_elektronik = '$alat_elektronik',
            merek = '$merek',
            nama_pelanggan = '$nama_pelanggan',
            no_hp = '$no_hp',
            tgl_masuk = '$tgl_masuk',
            tgl_selesai = '$tgl_selesai',
            status = '$status',
            biaya = $biaya
            WHERE id_perbaikan = $id");
        header("Location: index.php");
        exit;
    } else {
        $error = "Semua kolom wajib diisi!";
    }
}

if (isset($_POST['tambah_perbaikan'])) {
    $alat_elektronik = $_POST['alat_elektronik'];
    $merek = $_POST['merek'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $no_hp = $_POST['no_hp'];
    $tgl_masuk = $_POST['tgl_masuk'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $status = $_POST['status'];
    $biaya = $_POST['biaya'];

    if ($alat_elektronik && $merek && $nama_pelanggan && $no_hp && $tgl_masuk && $tgl_selesai && $status && $biaya !== "") {
        $conn->query("INSERT INTO perbaikan 
            (alat_elektronik, merek, nama_pelanggan, no_hp, tgl_masuk, tgl_selesai, status, biaya)
            VALUES 
            ('$alat_elektronik', '$merek', '$nama_pelanggan', '$no_hp', '$tgl_masuk', '$tgl_selesai', '$status', $biaya)");
    } else {
        $error = "Semua kolom wajib diisi!";
    }
}

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$perbaikan_result = $conn->query("SELECT * FROM perbaikan WHERE nama_pelanggan LIKE '%$keyword%' ORDER BY id_perbaikan DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reparasi Alat Elektronik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 30px auto;
        }

        h2 {
            margin-top: 0;
        }

        form {
            margin-bottom: 30px;
            backdrop-filter: blur(6px);
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 12px;
            max-width: 500px;
            box-shadow: 0 0 12px rgba(0,0,0,0.3);
        }

        input, select {
            padding: 10px;
            margin: 5px 0 15px 0;
            width: 100%;
            max-width: 400px;
            border: 1px solid #aaa;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.2);
            color: black; /* warna huruf dalam form */
            font-weight: bold;
        }

        input::placeholder {
            color: #888;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: black; /* warna label juga hitam */
        }

        button {
            padding: 10px 20px;
            background: #00bcd4;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 6px;
        }

        table {
            width: 100%;
            background: white;
            color: black;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background: #00bcd4;
            color: white;
        }

        a {
            color: #00bcd4;
            text-decoration: none;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 style="color: rgb(0, 0, 0);"><?= $edit_mode ? "Edit" : "Tambah" ?> Data Perbaikan</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id_perbaikan'] ?>">
        <?php endif; ?>

        <label>Alat Elektronik:</label>
        <select name="alat_elektronik" required>
            <option value="">-- Pilih Alat --</option>
            <?php 
            $alat_result = $conn->query("SELECT * FROM alat_elektronik");
            while ($alat = $alat_result->fetch_assoc()):
                $selected = ($edit_mode && $edit_data['alat_elektronik'] == $alat['jenis_alat']) ? 'selected' : '';
                echo "<option value='{$alat['jenis_alat']}' $selected>{$alat['jenis_alat']}</option>";
            endwhile;
            ?>
        </select>

        <label>Merek:</label>
        <input type="text" name="merek" value="<?= $edit_mode ? $edit_data['merek'] : '' ?>" required>

        <label>Nama Pelanggan:</label>
        <input type="text" name="nama_pelanggan" value="<?= $edit_mode ? $edit_data['nama_pelanggan'] : '' ?>" required>

        <label>No HP:</label>
        <input type="text" name="no_hp" value="<?= $edit_mode ? $edit_data['no_hp'] : '' ?>" required>

        <label>Tanggal Masuk:</label>
        <input type="date" name="tgl_masuk" value="<?= $edit_mode ? $edit_data['tgl_masuk'] : '' ?>" required>

        <label>Tanggal Selesai:</label>
        <input type="date" name="tgl_selesai" value="<?= $edit_mode ? $edit_data['tgl_selesai'] : '' ?>" required>

        <label>Status:</label>
        <input type="text" name="status" value="<?= $edit_mode ? $edit_data['status'] : '' ?>" required>

        <label>Biaya:</label>
        <input type="number" name="biaya" value="<?= $edit_mode ? $edit_data['biaya'] : '' ?>" required>

        <button type="submit" name="<?= $edit_mode ? 'update_perbaikan' : 'tambah_perbaikan' ?>">
            <?= $edit_mode ? 'Update' : 'Simpan' ?>
        </button>
    </form>

    <h2>Data Perbaikan</h2>

    <form method="GET" class="search-bar">
        <input type="text" name="keyword" placeholder="Cari nama pelanggan..." value="<?= $keyword ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Alat</th>
            <th>Merek</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Tgl Masuk</th>
            <th>Tgl Selesai</th>
            <th>Status</th>
            <th>Biaya</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $perbaikan_result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_perbaikan'] ?></td>
                <td><?= $row['alat_elektronik'] ?></td>
                <td><?= $row['merek'] ?></td>
                <td><?= $row['nama_pelanggan'] ?></td>
                <td><?= $row['no_hp'] ?></td>
                <td><?= $row['tgl_masuk'] ?></td>
                <td><?= $row['tgl_selesai'] ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= number_format($row['biaya'], 0, ',', '.') ?></td>
                <td>
                    <a href="index.php?edit=<?= $row['id_perbaikan'] ?>">Edit</a> |
                    <a href="index.php?hapus=<?= $row['id_perbaikan'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
            