<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Tambahkan animasi fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.15s ease-out;
            /* Percepat animasi menjadi 0.15s */
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Script untuk toggle dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
            dropdown.classList.toggle('fade-in');
        }
    </script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm w-full px-9 pr-4 py-3 flex items-center justify-between">
        <!-- Kiri: Logo dan Menu -->
        <div class="flex items-center space-x-6">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <a href="/index.php?modul=customer_dashboard" class="flex items-center space-x-2">
                    <img src="/image/logo.png" alt="Logo Restoo" class="w-8 h-8">
                    <span class="text-2xl font-bold">Restoo</span>
                </a>
            </div>
            <!-- Menu -->
            <ul class="flex space-x-6 text-gray-600 font-medium">
                <li>
                    <a href="/index.php?modul=rekomendasi" class="hover:text-black">Rekomendasi</a>
                </li>
            </ul>
        </div>

        <!-- Kanan: Ikon Pencarian dan Profil -->
        <div class="flex items-center space-x-4">
            <!-- Tombol Pencarian -->
            <a href="/views/customer/search.php" class="bg-purple-500 text-white rounded-full w-8 h-8 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </a>
            <!-- Profil dengan Dropdown -->
            <div class="relative">
                <div onclick="toggleDropdown()" class="bg-purple-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-semibold cursor-pointer">
                    <?= htmlspecialchars(substr($username, 0, 2)); ?>
                </div>
                <!-- Dropdown Menu -->
                <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg py-2">
                    <div class="px-4 py-2 text-sm text-gray-700">
                        <strong><?= htmlspecialchars($username); ?></strong>
                    </div>
                    <hr class="my-2">
                    <a href="index.php?modul=riwayat" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pembelian</a>
                    <a href="index.php?modul=logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Keluar</a>
                </div>
            </div>
        </div>
    </nav>
</body>

</html>