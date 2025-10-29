<?php
session_start();
require 'koneksi.php';
// Gerbang Keamanan
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}
// Logika tambah.php dari M7 [cite: 689-693]
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO mahasiswa (nama, nim, jurusan) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['nama'], $_POST['nim'], $_POST['jurusan']]);
    header("Location: dashboard.php");
    exit;
}
$nama_pengguna = htmlspecialchars($_SESSION['nama'] ?? 'Pengguna');
$inisial_nama = strtoupper(substr($nama_pengguna, 0, 2));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa - STMIK IKMI Cirebon</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar group"> ... (Salin seluruh <nav> ... </nav> dari dashboard.php) ... </nav>

    <main class="main-container">
        <div class="content-card max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Tambah Data Mahasiswa Baru</h1>
            
            <form method="POST" class="space-y-6">
                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap</Tlabel>
                    <input type="text" id="nama" name="nama" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="nim" class="form-label">NIM</Tlabel>
                    <input type="text" id="nim" name="nim" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="jurusan" class="form-label">Jurusan</Tlabel>
                    <input type="text" id="jurusan" name="jurusan" class="form-input" required>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="btn">Simpan Data</button>
                    <a href="dashboard.php" class="btn bg-gray-600 hover:bg-gray-700">Batal</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>