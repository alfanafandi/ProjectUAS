<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="image\logo.png" type="image/x-icon">
    <title>Isi Saldo</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-green-400 to-green-200 min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="sticky top-0 bg-white shadow-md z-10">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
    </header>

    <!-- Container utama -->
    <main class="flex-grow flex items-center justify-center">
        <!-- Form Isi Saldo -->
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg transform transition-transform hover:scale-105">
            <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Masukkan Jumlah Saldo</h2>
            <form action="index.php?modul=transaksi&fitur=add" method="POST">
                <input type="hidden" name="pengguna" value="<?php echo $_SESSION['username']; ?>">
                <div class="mb-6">
                    <label for="jumlah" class="block text-gray-700 font-medium mb-2">Jumlah Saldo (Rp)</label>
                    <input type="number" id="jumlah" name="jumlah" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" placeholder="Masukkan jumlah saldo" required>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-green-500 text-white w-full py-4 rounded-lg shadow-md hover:bg-green-600 transition">Konfirmasi</button>
                    <a href="index.php?modul=customer_dashboard" class="bg-gray-200 text-gray-700 w-full py-4 rounded-lg shadow-md hover:bg-gray-300 transition text-center">Batal</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>