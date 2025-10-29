<?php
require 'koneksi.php';
$id = $_GET['id'] ?? die("ID tidak valid");
$stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
$stmt->execute([$id]);
header("Location: index.php?pesan dihapus");
exit;
?>