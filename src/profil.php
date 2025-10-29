<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$nim_user = $_SESSION['nim'];
$stmt = $pdo->prepare("SELECT nama, nim, email, foto FROM users WHERE nim = ?");
$stmt->execute([$nim_user]);
$user = $stmt->fetch();
if (!$user) die("Data pengguna tidak ditemukan.");

$nama_pengguna = htmlspecialchars($user['nama']);
$nim_tampil = htmlspecialchars($user['nim']);
$email_tampil = htmlspecialchars($user['email']);
$foto_profil = htmlspecialchars($user['foto']);
$inisial_nama = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nama_pengguna), 0, 1));
if (empty($inisial_nama)) $inisial_nama = 'U';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - STMIK IKMI Cirebon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-slate-100 antialiased">

     <nav class="bg-white shadow-md h-20 flex justify-between items-center px-6 sm:px-10 sticky top-0 z-50 group">
        <a href="dashboard.php" class="text-2xl font-bold text-blue-800">STMIK IKMI</a>
        <div class="relative group">
            <button type="button" class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-slate-100 transition-colors">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold border-2 border-blue-200 text-sm">
                    <?= $inisial_nama ?>
                </div>
                <span class="hidden sm:block font-semibold text-gray-700"><?= $nama_pengguna ?></span>
                <svg class="w-5 h-5 text-gray-500 hidden sm:block ml-1 group-hover:rotate-180 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </button>
            <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl overflow-hidden z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 ease-out transform origin-top-right scale-95 group-hover:scale-100">
                <a href="profil.php" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                    <span>Profil Saya</span>
                </a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm text-red-600 border-t border-gray-100 hover:bg-slate-100 transition-colors">
                     <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V5.414l7.293 7.293a1 1 0 001.414-1.414L5.414 4H12a1 1 0 100-2H4a1 1 0 00-1 1z" clip-rule="evenodd" /></svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-4 sm:p-10">
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-10 max-w-3xl mx-auto animate-fadeInUp">
            <div class="text-center mb-10">
                 <img src="asset/images/<?= $foto_profil ?>" alt="Foto Profil <?= $nama_pengguna ?>" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-blue-200 shadow-lg object-cover bg-gray-200">
                <h1 class="text-3xl font-bold text-gray-800"><?= $nama_pengguna ?></h1>
                <p class="text-lg text-gray-600 mt-1">Mahasiswa Aktif</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-slate-50 p-4 rounded-lg border border-gray-200">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Lengkap</label>
                    <p class="text-lg font-medium text-gray-900 mt-1"><?= $nama_pengguna ?></p>
                </div>
                 <div class="bg-slate-50 p-4 rounded-lg border border-gray-200">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide">NIM</label>
                    <p class="text-lg font-medium text-gray-900 mt-1"><?= $nim_tampil ?></p>
                </div>
                 <div class="col-span-1 md:col-span-2 bg-slate-50 p-4 rounded-lg border border-gray-200"> 
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</label>
                    <p class="text-lg font-medium text-gray-900 mt-1"><?= $email_tampil ?></p>
                </div>
            </div>
             <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-center items-center gap-4">
                 <a href="edit_profil.php" class="inline-block px-6 py-3 rounded-lg font-semibold text-white bg-blue-700 hover:bg-blue-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 w-full sm:w-auto text-center">Edit Profil</a>
                 <a href="dashboard.php" class="inline-block px-6 py-3 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition-all duration-300 w-full sm:w-auto text-center">Kembali ke Dashboard</a>
            </div>
        </div>
    </main>

    <style> @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } } .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; } </style>
</body>
</html>