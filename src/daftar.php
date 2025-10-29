<?php
require 'koneksi.php';

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100) NOT NULL, nim VARCHAR(8) NOT NULL UNIQUE, email VARCHAR(100) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, foto VARCHAR(255) DEFAULT 'default.png')");
} catch (\PDOException $e) {
     if ($e->getCode() !== '42S01') { error_log("Error creating table: " . $e->getMessage()); }
}

$errors = [];
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $nim = $_POST['nim'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $foto_final_name = 'default.png'; 

    if (strlen($nama) < 2) $errors[] = "Nama minimal 2 karakter.";
    if (!ctype_digit($nim) || strlen($nim) !== 8) $errors[] = "NIM harus 8 digit angka.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid.";
    if (strlen($password) < 6) $errors[] = "Password minimal 6 karakter.";

    if (empty($errors) && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024; 

        if (!in_array($fileExtension, $allowedfileExtensions)) {
            $errors[] = "Format foto hanya boleh jpg, jpeg, png, atau gif.";
        } elseif ($fileSize > $maxFileSize) {
            $errors[] = "Ukuran foto maksimal 2MB.";
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fileTmpPath);
            finfo_close($finfo);
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($mime, $allowedMimeTypes)) {
                $errors[] = "Jenis file tidak valid.";
            } else {
                $sanitized_nama = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama);
                $newFileName = $nim . '-' . $sanitized_nama . '.' . $fileExtension;
                $uploadFileDir = __DIR__ . '/asset/images/'; 
                $dest_path = $uploadFileDir . $newFileName;
                if (!is_dir($uploadFileDir)) {
                    if (!mkdir($uploadFileDir, 0777, true)) { $errors[] = 'Gagal membuat direktori upload.'; }
                }
                if (empty($errors) && move_uploaded_file($fileTmpPath, $dest_path)) {
                  $foto_final_name = $newFileName; 
                } else {
                  if(empty($errors)) { $errors[] = 'Gagal memindahkan file yang diunggah.'; }
                }
            }
        }
    } elseif (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Terjadi kesalahan saat mengunggah file. Kode Error: ' . $_FILES['foto']['error'];
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (nama, nim, email, password, foto) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $nim, $email, $hashed_password, $foto_final_name]);
            $success_msg = "Pendaftaran berhasil!";
        } catch (\PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                 if (strpos($e->getMessage(), 'nim') !== false) { $errors[] = "NIM sudah terdaftar."; } 
                 elseif (strpos($e->getMessage(), 'email') !== false) { $errors[] = "Email sudah terdaftar."; } 
                 else { $errors[] = "Data duplikat terdeteksi."; }
            } else {
                $errors[] = "Terjadi kesalahan pada database: " . $e->getMessage();
                 error_log("Database error: " . $e->getMessage()); 
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - STMIK IKMI Cirebon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
             background-color: #f8fafc;
             background-image: repeating-linear-gradient( -45deg, #ffffff, #ffffff 10px, #f8fafc 10px, #f8fafc 20px );
        }
         input[type="file"]::file-selector-button {
            background: #eff6ff; /* bg-blue-50 */ color: #3b82f6; /* text-blue-600 */ border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500; cursor: pointer; transition: background-color 0.2s ease; margin-right: 1rem;
        }
        input[type="file"]::file-selector-button:hover { background: #dbeafe; /* bg-blue-100 */ }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10 antialiased">
    <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-xl w-full max-w-md m-4 transform transition-all hover:scale-[1.01] duration-300">
        <div class="text-center mb-8">
             <div class="inline-block p-2 bg-blue-100 rounded-full mb-4">
               <svg class="w-10 h-10 text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" ><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Akun Baru</h1>
            <p class="text-gray-600 mt-2 text-sm">Portal Mahasiswa STMIK IKMI Cirebon</p>
        </div>
        <form method="POST" enctype="multipart/form-data" class="mt-8 space-y-5">
            <?php if (!empty($errors)): ?>
                <ul class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg space-y-1 text-sm" role="alert">
                    <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if (!empty($success_msg)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm" role="alert">
                    <p><?= htmlspecialchars($success_msg) ?> <a href="login.php" class="font-bold hover:underline">Masuk di sini</a>.</p>
                </div>
            <?php endif; ?>
            <div class="space-y-2">
                <label for="nama" class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" required value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="space-y-2">
                <label for="nim" class="text-sm font-medium text-gray-700">NIM (8 digit)</label>
                <input type="text" id="nim" name="nim" required value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="space-y-2">
                <label for="password" class="text-sm font-medium text-gray-700">Password (min. 6 karakter)</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="space-y-2">
                <label for="foto" class="text-sm font-medium text-gray-700">Foto Profil (Opsional, Max 2MB)</label>
                <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/gif" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                    Daftar Akun
                </button>
            </div>
            <div class="text-center text-sm text-gray-600">
                Sudah punya akun? <a href="login.php" class="font-medium text-blue-700 hover:text-blue-600 hover:underline">Masuk di sini</a>
            </div>
        </form>
    </div>
</body>
</html>