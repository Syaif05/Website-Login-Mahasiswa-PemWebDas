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

$nama_sekarang = htmlspecialchars($user['nama']);
$foto_sekarang = htmlspecialchars($user['foto']);
$inisial_nama = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nama_sekarang), 0, 1));
if (empty($inisial_nama)) $inisial_nama = 'U'; 

$errors = [];
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_baru = trim($_POST['nama'] ?? '');
    $nim_hidden = $_POST['nim'] ?? ''; 
    $foto_lama = $_POST['foto_lama'] ?? 'default.png';
    $foto_baru_final = $foto_lama; 

    if ($nim_hidden !== $nim_user) { $errors[] = "NIM tidak valid."; }
    if (strlen($nama_baru) < 2) { $errors[] = "Nama baru minimal 2 karakter."; }

    $nama_berubah = ($nama_baru !== $user['nama']);
    $foto_diupload = (isset($_FILES['foto_baru']) && $_FILES['foto_baru']['error'] === UPLOAD_ERR_OK);

    if (empty($errors) && $foto_diupload) {
        $fileTmpPath = $_FILES['foto_baru']['tmp_name'];
        $fileName = $_FILES['foto_baru']['name'];
        $fileSize = $_FILES['foto_baru']['size'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024;

        if (!in_array($fileExtension, $allowedfileExtensions)) { $errors[] = "Format foto baru hanya boleh jpg, jpeg, png, atau gif."; } 
        elseif ($fileSize > $maxFileSize) { $errors[] = "Ukuran foto baru maksimal 2MB."; } 
        else {
             $finfo = finfo_open(FILEINFO_MIME_TYPE);
             $mime = finfo_file($finfo, $fileTmpPath);
             finfo_close($finfo);
             $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
             if (!in_array($mime, $allowedMimeTypes)) { $errors[] = "Jenis file foto baru tidak valid."; } 
             else {
                $sanitized_nama_baru = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama_baru);
                $newFileName = $nim_user . '-' . $sanitized_nama_baru . '.' . $fileExtension;
                $uploadFileDir = __DIR__ . '/asset/images/';
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    if ($foto_lama !== 'default.png' && file_exists($uploadFileDir . $foto_lama) && $foto_lama !== $newFileName) { @unlink($uploadFileDir . $foto_lama); }
                    $foto_baru_final = $newFileName; 
                } else { $errors[] = 'Gagal menyimpan foto baru.'; error_log("Gagal move_uploaded_file ke $dest_path"); }
            }
        }
    } elseif (empty($errors) && $nama_berubah && !$foto_diupload && $foto_lama !== 'default.png') {
        $fileNameCmps = explode(".", $foto_lama);
        $fileExtension = strtolower(end($fileNameCmps));
        $sanitized_nama_baru = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama_baru);
        $newFileName = $nim_user . '-' . $sanitized_nama_baru . '.' . $fileExtension;
        $uploadFileDir = __DIR__ . '/asset/images/';
        $old_path = $uploadFileDir . $foto_lama;
        $new_path = $uploadFileDir . $newFileName;
        if (file_exists($old_path) && $old_path !== $new_path) {
            if (rename($old_path, $new_path)) { $foto_baru_final = $newFileName; } 
            else { $errors[] = 'Gagal me-rename file foto lama.'; error_log("Gagal rename foto dari $old_path ke $new_path"); }
        } elseif ($old_path !== $new_path) { 
             error_log("File foto lama tidak ditemukan untuk di-rename: $old_path");
             $foto_baru_final = $newFileName;
        } else { $foto_baru_final = $newFileName; }
    }

    if (empty($errors)) {
        try {
            $updateStmt = $pdo->prepare("UPDATE users SET nama = ?, foto = ? WHERE nim = ?");
            $updateStmt->execute([$nama_baru, $foto_baru_final, $nim_user]);
            $_SESSION['nama'] = $nama_baru; 
            $success_msg = "Profil berhasil diperbarui!";
            $nama_sekarang = htmlspecialchars($nama_baru);
            $foto_sekarang = htmlspecialchars($foto_baru_final);
            $inisial_nama = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nama_sekarang), 0, 1));
             if (empty($inisial_nama)) $inisial_nama = 'U'; 
        } catch (\PDOException $e) { $errors[] = "Gagal memperbarui database: " . $e->getMessage(); error_log("Update DB error: " . $e->getMessage()); }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - STMIK IKMI Cirebon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Poppins', sans-serif; } 
         input[type="file"]::file-selector-button { background: #eff6ff; color: #3b82f6; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500; cursor: pointer; transition: background-color 0.2s ease; margin-right: 1rem; }
        input[type="file"]::file-selector-button:hover { background: #dbeafe; }
    </style>
</head>
<body class="bg-slate-100 antialiased">
     <nav class="bg-white shadow-md h-20 flex justify-between items-center px-6 sm:px-10 sticky top-0 z-50 group">
        <a href="dashboard.php" class="text-2xl font-bold text-blue-800">STMIK IKMI</a>
        <div class="relative group">
             <button type="button" class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-slate-100 transition-colors">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold border-2 border-blue-200 text-sm">
                    <?= $inisial_nama ?>
                </div>
                <span class="hidden sm:block font-semibold text-gray-700"><?= $nama_sekarang ?></span> 
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
            <h1 class="text-2xl sm:text-3xl font-bold mb-8 text-gray-800">Edit Profil</h1>

            <?php if (!empty($errors)): ?>
                <ul class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg space-y-1 text-sm mb-6" role="alert">
                    <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul>
            <?php endif; ?>
            
            <?php if (!empty($success_msg)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm mb-6" role="alert">
                    <p><?= htmlspecialchars($success_msg) ?> <a href="profil.php" class="font-bold hover:underline">Lihat Profil</a>.</p>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="nim" value="<?= htmlspecialchars($nim_user) ?>">
                <input type="hidden" name="foto_lama" value="<?= $foto_sekarang ?>">

                <div class="space-y-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" value="<?= $nama_sekarang ?>" required>
                </div>

                 <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">NIM</label>
                    <p class="text-gray-600 bg-slate-100 px-4 py-3 rounded-md border border-gray-200"><?= htmlspecialchars($nim_user) ?> (Tidak dapat diubah)</p>
                </div>
                 <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                     <p class="text-gray-600 bg-slate-100 px-4 py-3 rounded-md border border-gray-200"><?= htmlspecialchars($user['email']) ?> (Tidak dapat diubah)</p>
                </div>

                <div class="space-y-2">
                    <label for="foto_baru" class="block text-sm font-medium text-gray-700">Ganti Foto Profil (Opsional, Max 2MB)</label>
                    <div class="flex items-center gap-4 mt-1">
                        <img src="asset/images/<?= $foto_sekarang ?>" alt="Foto saat ini" class="w-16 h-16 rounded-full object-cover border border-gray-300 bg-gray-100">
                        <input type="file" id="foto_baru" name="foto_baru" accept="image/png, image/jpeg, image/gif" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer focus:outline-none file:cursor-pointer file:border-0 file:py-2 file:px-4 file:rounded-l-lg file:mr-4 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                     <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengganti foto.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="w-full sm:w-auto inline-block px-6 py-3 rounded-lg font-semibold text-white bg-blue-700 hover:bg-blue-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Simpan Perubahan</button>
                    <a href="profil.php" class="w-full sm:w-auto inline-block text-center px-6 py-3 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition-all duration-300">Batal</a>
                </div>
            </form>
        </div>
    </main>
    
    <style> @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } } .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; } </style>
</body>
</html>