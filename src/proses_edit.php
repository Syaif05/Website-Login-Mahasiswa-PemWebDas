<?php
require 'koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE mahasiswa SET nama = ?, nim = ?, jurusan = ? WHERE id = ?");
    $stmt->execute([$_POST['nama'], $_POST['nim'], $_POST['jurusan'], $_POST['id']]);
    header("Location: index.php?pesan diupdate");
    exit;
}
?>