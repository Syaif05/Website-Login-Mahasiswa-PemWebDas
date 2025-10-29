<?php
session_start(); 
require 'koneksi.php';

$step = 1; 
$email = '';
$errors = [];
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email_check'])) {
        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email tidak valid.";
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $step = 2; 
            } else {
                $errors[] = "Email tidak ditemukan dalam sistem.";
            }
        }
    } 
    elseif (isset($_POST['password_reset'])) {
        $email = $_POST['email'] ?? ''; 
        $password_baru = $_POST['password_baru'] ?? '';
        $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
        $step = 2; 

        if (strlen($password_baru) < 6) {
            $errors[] = "Password baru minimal 6 karakter.";
        } elseif ($password_baru !== $konfirmasi_password) {
            $errors[] = "Konfirmasi password tidak cocok.";
        } else {
             if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 $errors[] = "Email tidak valid."; 
            } else {
                try {
                    $hashed_password_baru = password_hash($password_baru, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                    $updateStmt->execute([$hashed_password_baru, $email]);
                    
                    if ($updateStmt->rowCount() > 0) {
                        $success_msg = "Password berhasil diubah.";
                        $step = 3; 
                    } else {
                         $errors[] = "Gagal mengubah password. Email mungkin tidak ditemukan.";
                    }

                } catch (\PDOException $e) {
                    $errors[] = "Terjadi kesalahan database: " . $e->getMessage();
                     error_log("Password reset error: " . $e->getMessage());
                }
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
    <title>Lupa Password - STMIK IKMI Cirebon</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style type="text/tailwindcss">
        body {
            font-family: 'Poppins', sans-serif;
             background-color: #f8fafc;
             background-image: repeating-linear-gradient(
                -45deg, #ffffff, #ffffff 10px, #f8fafc 10px, #f8fafc 20px
            );
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-xl w-full max-w-md m-4">
        <div class="text-center mb-8">
             <div class="inline-block p-2 bg-blue-100 rounded-full mb-4">
                 <svg class="w-10 h-10 text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Reset Password</h1>
            <p class="text-gray-600 mt-2 text-sm">Portal Mahasiswa STMIK IKMI Cirebon</p>
        </div>

        <?php if (!empty($errors)): ?>
            <ul class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg space-y-1 text-sm mb-6" role="alert">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <?php if (!empty($success_msg)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm mb-6" role="alert">
                <p><?= htmlspecialchars($success_msg) ?> Silakan <a href="login.php" class="font-bold hover:underline">login</a> dengan password baru Anda.</p>
            </div>
        <?php endif; ?>

        <?php if ($step === 1): ?>
            <form method="POST" class="space-y-6">
                <p class="text-sm text-gray-600">Masukkan email yang terdaftar pada akun Anda untuk melanjutkan.</p>
                <input type="hidden" name="email_check" value="1">
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-gray-700">Email Terdaftar</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                        Cari Akun
                    </button>
                </div>
            </form>
        <?php elseif ($step === 2 && empty($success_msg)): ?>
            <form method="POST" class="space-y-6">
                <p class="text-sm text-gray-600">Akun dengan email <strong><?= htmlspecialchars($email) ?></strong> ditemukan. Masukkan password baru Anda.</p>
                <input type="hidden" name="password_reset" value="1">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                <div class="space-y-2">
                    <label for="password_baru" class="text-sm font-medium text-gray-700">Password Baru (min. 6 karakter)</label>
                    <input type="password" id="password_baru" name="password_baru" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                 <div class="space-y-2">
                    <label for="konfirmasi_password" class="text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                 <div>
                    <button type="submit" class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                        Reset Password
                    </button>
                </div>
            </form>
        <?php endif; ?>

         <div class="text-center text-sm text-gray-600 mt-6">
            <a href="login.php" class="font-medium text-blue-700 hover:text-blue-600 hover:underline">
                Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>