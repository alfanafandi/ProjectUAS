<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="image/logo.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-gray-100 via-gray-200 to-gray-300 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
    <?php include __DIR__ . '/../includes/navbar_admin.php'; ?>

    <!-- Main Container -->
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../includes/sidebar_admin.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Dashboard Header -->
            <h2 class="text-3xl font-bold mb-6 text-gray-800">Dashboard Admin</h2>

            <!-- Statistik Utama -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                <!-- Total Transaksi -->
                <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Total Transaksi</h3>
                    <p class="text-5xl font-bold text-green-500 mt-4"><?= $totalTransaksi; ?></p>
                </div>

                <!-- Total Pengguna -->
                <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Total Pengguna</h3>
                    <p class="text-5xl font-bold text-blue-500 mt-4"><?= $totalPengguna; ?></p>
                </div>

                <!-- Total Restoran -->
                <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Total Restoran</h3>
                    <p class="text-5xl font-bold text-yellow-500 mt-4"><?= $totalRestoran; ?></p>
                </div>
            </div>
        </main>
    </div>
</body>

</html>