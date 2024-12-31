<?php
require_once '../../model/restoran_model.php';
require_once '../../model/diskon_model.php';

session_start();

$modelRestoran = new RestoranModel();

$restoran_id_login = $_SESSION['restoran_id'];

echo $restoran_id_login;

// Ambil data restoran berdasarkan ID login
$restoranLogin = $modelRestoran->getRestoranById($restoran_id_login);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Diskon</title>
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
            <!-- Formulir Input Diskon -->
            <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Diskon</h2>
                <form action="/../../index.php?modul=diskon&fitur=add" method="POST">
                    <!-- Nama Restoran (Hanya untuk restoran yang login) -->
                    <div class="mb-4">
                        <label for="diskon_restoran" class="block text-gray-700 text-sm font-bold mb-2">Nama Restoran:</label>
                        <input type="text" id="diskon_restoran" name="diskon_restoran" value="<?php echo htmlspecialchars($restoranLogin->restoran_nama); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
                        <input type="hidden" name="diskon_restoran" value="<?php echo htmlspecialchars($restoranLogin->restoran_id); ?>">
                    </div>

                    <!-- Nama Diskon -->
                    <div class="mb-4">
                        <label for="diskon_nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Diskon:</label>
                        <input type="text" id="diskon_nama" name="diskon_nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Nama Diskon" required>
                    </div>

                    <!-- Presentase Diskon -->
                    <div class="mb-4">
                        <label for="diskon_persen" class="block text-gray-700 text-sm font-bold mb-2">Presentase Diskon (%):</label>
                        <input type="number" id="diskon_persen" name="diskon_persen" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Presentase Diskon" required min="0" max="100">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Tambah Diskon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>