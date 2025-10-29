<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$search = trim($_GET['search'] ?? '');
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 5; 
$offset = ($page - 1) * $limit;

$countParams = [];
$dataParams = [];
$countSql = "SELECT COUNT(*) FROM users";
$dataSql = "SELECT id, nama, nim, email, foto FROM users";

if (!empty($search)) {
    $searchTerm = "%{$search}%";
    $countSql .= " WHERE nim LIKE :search1 OR nama LIKE :search2";
    $dataSql .= " WHERE nim LIKE :search1 OR nama LIKE :search2";
    $countParams = [':search1' => $searchTerm, ':search2' => $searchTerm];
    $dataParams = [':search1' => $searchTerm, ':search2' => $searchTerm];
}

$totalStmt = $pdo->prepare($countSql);
$totalStmt->execute($countParams);
$totalData = $totalStmt->fetchColumn();
$totalPages = ceil($totalData / $limit);
if ($page > $totalPages && $totalData > 0) {
     header("Location: ?page=" . $totalPages . "&search=" . urlencode($search)); exit;
}


$dataSql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($dataSql);

if (!empty($search)) {
    $stmt->bindValue(':search1', $dataParams[':search1'], PDO::PARAM_STR);
    $stmt->bindValue(':search2', $dataParams[':search2'], PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$users = $stmt->fetchAll();

$nama_pengguna = htmlspecialchars($_SESSION['nama'] ?? 'Pengguna');
$inisial_nama = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nama_pengguna), 0, 1));
if (empty($inisial_nama)) $inisial_nama = 'U';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - STMIK IKMI Cirebon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .pagination a, .pagination span { display: inline-block; padding: 0.5rem 1rem; margin: 0 0.25rem; border: 1px solid #e2e8f0; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s ease; }
        .pagination a:hover { background-color: #f1f5f9; color: #1e40af; }
        .pagination span.current { background-color: #1d4ed8; color: white; border-color: #1d4ed8; z-index: 10; cursor: default; }
        .pagination span.disabled { color: #9ca3af; border-color: #e2e8f0; cursor: not-allowed; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; }
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
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-10 animate-fadeInUp">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 mb-6 border-b border-gray-200 gap-4">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Daftar Pengguna</h1>
                <form method="GET" class="flex w-full sm:w-auto">
                    <input type="text" name="search" placeholder="Cari NIM atau Nama..." value="<?= htmlspecialchars($search) ?>" class="flex-grow sm:flex-grow-0 w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-sm">
                    <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded-r-lg hover:bg-blue-800 transition-colors text-sm font-semibold whitespace-nowrap">
                         <svg class="w-5 h-5 inline sm:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                         <span class="hidden sm:inline">Cari</span>
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="dashboard.php" class="ml-3 px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors text-sm font-semibold whitespace-nowrap">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="w-full overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full min-w-[640px] text-left">
                    <thead class="bg-slate-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($users)): ?>
                            <tr><td colspan="4" class="text-center py-10 text-gray-500"><?= !empty($search) ? 'Tidak ada pengguna ditemukan.' : 'Belum ada pengguna terdaftar.' ?></td></tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                            <tr class="hover:bg-slate-50 transition-colors duration-150">
                                <td class="px-6 py-4"><img src="asset/images/<?= htmlspecialchars($u['foto'])?>" alt="Foto <?= htmlspecialchars($u['nama']) ?>" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 shadow-sm"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($u['nim']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?= htmlspecialchars($u['nama']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($u['email']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <div class="mt-8 flex justify-center pagination">
                <?php if ($page > 1): ?> <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">« Prev</a> <?php else: ?> <span class="disabled">« Prev</span> <?php endif; ?>
                <?php 
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);

                    if ($startPage > 1) echo '<a href="?page=1&search='.urlencode($search).'">1</a><span class="disabled">...</span>';

                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= $i == $page ? 'current' : '' ?>"><?= $i ?></a>
                <?php endfor; 
                
                    if ($endPage < $totalPages) echo '<span class="disabled">...</span><a href="?page='.$totalPages.'&search='.urlencode($search).'">'.$totalPages.'</a>';
                ?>
                <?php if ($page < $totalPages): ?> <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next »</a> <?php else: ?> <span class="disabled">Next »</span> <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </main>
</body>
</html>