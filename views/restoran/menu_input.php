<?php
require_once '../../model/restoran_model.php';
require_once '../../model/menu_model.php';

session_start();

$modelRestoran = new RestoranModel();

$restoran_id_login = $_SESSION['restoran_id'];

// Ambil data restoran berdasarkan ID login
$restoranLogin = $modelRestoran->getRestoranById($restoran_id_login);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
    <?php include '../includes/navbar_restoran.php'; ?>

    <!-- Main container -->
    <div class="flex">
        <!-- Sidebar -->
        <?php include '../includes/sidebar_restoran.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Formulir Input Menu -->
            <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Menu</h2>
                <form action="/ProjectDB/index.php?modul=menu&fitur=add" method="POST" enctype="multipart/form-data">

                    <!-- Nama Restoran (Hanya untuk restoran yang login) -->
                    <div class="mb-4">
                        <label for="menu_restoran" class="block text-gray-700 text-sm font-bold mb-2">Nama Restoran:</label>
                        <input type="text" id="menu_restoran" name="menu_restoran" value="<?php echo htmlspecialchars($restoranLogin->restoran_nama); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
                        <input type="hidden" name="menu_restoran" value="<?php echo htmlspecialchars($restoranLogin->restoran_id); ?>">
                    </div>

                    <!-- Nama Menu -->
                    <div class="mb-4">
                        <label for="menu_nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Menu:</label>
                        <input type="text" id="menu_nama" name="menu_nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Nama Menu" required>
                    </div>

                    <!-- Kategori Menu -->
                    <div class="mb-4">
                        <label for="menu_kategori" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
                        <select id="menu_kategori" name="menu_kategori" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Snack">Snack</option>
                        </select>
                    </div>

                    <!-- Harga Menu -->
                    <div class="mb-4">
                        <label for="menu_harga" class="block text-gray-700 text-sm font-bold mb-2">Harga:</label>
                        <input type="number" id="menu_harga" name="menu_harga" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Harga Menu" required min="0">
                    </div>

                    <!-- Gambar Menu -->
                    <div class="mb-4">
                        <label for="menu_gambar" class="block text-gray-700 text-sm font-bold mb-2">Gambar Menu:</label>
                        <input type="file" id="menu_gambar" name="menu_gambar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Tambah Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>