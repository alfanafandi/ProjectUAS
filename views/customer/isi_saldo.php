<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Saldo</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="sticky top-0 bg-white shadow-md z-10">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
    </header>

    <!-- Container utama -->
    <main class="flex-grow flex items-center justify-center">
        <!-- Form Isi Saldo -->
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Masukkan Jumlah Saldo</h2>
            <form action="/../../index.php?modul=transaksi&fitur=add" method="POST">
                <input type="hidden" name="pengguna" value="<?php echo $_SESSION['user_id']; ?>">
                <div class="mb-4">
                    <label for="jumlah" class="block text-gray-700 font-medium mb-2">Jumlah Saldo (Rp)</label>
                    <input type="number" id="jumlah" name="jumlah" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" placeholder="Masukkan jumlah saldo" required>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-green-600 transition">Konfirmasi</button>
                    <a href="/index.php?modul=customer_dashboard" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg shadow-md hover:bg-gray-300 transition">Batal</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>