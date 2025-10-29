<?php
session_start();
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = trim($_POST['nim'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($nim) || empty($password)) {
        $error = "NIM dan password wajib diisi!";
    } else {
        require 'koneksi.php';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE nim = ?");
        $stmt->execute([$nim]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['nim'] = $user['nim'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "NIM atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - STMIK IKMI Cirebon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            background-image: repeating-linear-gradient(
                -45deg, #ffffff, #ffffff 10px, #f8fafc 10px, #f8fafc 20px
            );
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen antialiased">
    <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-xl w-full max-w-md m-4 transform transition-all hover:scale-[1.01] duration-300">
        <div class="text-center mb-8">
             <div class="inline-block p-2 bg-blue-100 rounded-full mb-4">
                 <svg class="w-10 h-10 text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Masuk ke Akun Anda</h1>
            <p class="text-gray-600 mt-2 text-sm">STMIK IKMI Cirebon Portal</p>
        </div>
        <form method="POST" class="space-y-6">
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm" role="alert">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>
            <div class="space-y-2">
                <label for="nim" class="text-sm font-medium text-gray-700">NIM</label>
                <input type="text" id="nim" name="nim" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="Masukkan NIM Anda">
            </div>
            <div class="space-y-2 relative">
                <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="••••••••••">
                 <div class="text-right text-sm mt-2">
                     <a href="lupa_password.php" class="font-medium text-blue-700 hover:text-blue-600 hover:underline">
                        Lupa Password?
                    </a>
                </div>
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 focus:ring-opacity-75 transition duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                    Sign In
                </button>
            </div>
            <div class="text-center text-sm text-gray-600">
                Belum punya akun? 
                <a href="daftar.php" class="font-medium text-blue-700 hover:text-blue-600 hover:underline">
                    Daftar di sini
                </a>
            </div>
        </form>
    </div>
</body>
</html>