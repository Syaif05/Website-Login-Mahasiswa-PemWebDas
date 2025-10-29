<?php
require 'koneksi.php';

$pdo->exec("
    CREATE TABLE IF NOT EXISTS mahasiswa (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(30) NOT NULL,
        nim VARCHAR(8) NOT NULL,
        jurusan VARCHAR(50)
    )
");

$stmt = $pdo->query("SELECT * FROM mahasiswa");
$mahasiswa = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manajemen MAhasiswa</title>
</head>
<body>
    <?php if (empty($mahasiswa)): ?>
        <p>Belum ada data mahasiswa</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Jurusan</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($mahasiswa as $m): ?>
            <tr>
                <td><?= $m['id'] ?></td>
                <td><?= htmlspecialchars($m ['nama'])?></td>
                <td><?= htmlspecialchars($m ['nim'])?></td>
                <td><?= htmlspecialchars($m ['jurusan'])?></td>
                <td>
                    <a href="edit.php?id=<?= $m['id'] ?>">Edit</a>
                    <a href="hapus.php?id=<?= $m['id'] ?>" onclick="return confirm('Yakin hapus?')">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>