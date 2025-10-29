<?php
require 'koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO mahasiswa (nama, nim, jurusan) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['nama'], $_POST['nim'], $_POST['jurusan']]);
    
    header("Location: index.php?pesan ditambah");
    exit;
}
?>