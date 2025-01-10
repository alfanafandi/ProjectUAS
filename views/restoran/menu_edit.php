<?php
require_once __DIR__ . '/../../model/menu_model.php';
require_once __DIR__ . '/../../model/restoran_model.php';
$restoran_id_login = $_SESSION['restoran_id'];

$obj_modelRestoran = new RestoranModel();
$restoranLogin = $obj_modelRestoran->getRestoranById($restoran_id_login);
$obj_modelMenu = new MenuModel($obj_modelRestoran);

// Ambil data menu yang akan di-edit
$menu_id = $_GET['id'];
$menu = $obj_modelMenu->getMenuById($menu_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
    <?php include __DIR__ . '/../includes/navbar_restoran.php'; ?>

    <!-- Main container -->
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../includes/sidebar_restoran.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Formulir Edit Menu -->
            <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Menu</h2>
                <form action="/ProjectDB/index.php?modul=menu&fitur=update&id=<?php echo $menu['menu_id']; ?>" method="POST" enctype="multipart/form-data">

                    <!-- Nama Restoran -->
                    <div class="mb-4">
                        <label for="menu_restoran" class="block text-gray-700 text-sm font-bold mb-2">Nama Restoran:</label>
                        <input type="text" id="menu_restoran" name="menu_restoran" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($restoranLogin->restoran_nama); ?>" readonly>
                        <input type="hidden" name="menu_restoran" value="<?php echo htmlspecialchars($restoranLogin->restoran_id); ?>">
                    </div>

                    <!-- Nama Menu -->
                    <div class="mb-4">
                        <label for="menu_nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Menu:</label>
                        <input type="text" id="menu_nama" name="menu_nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($menu['menu_nama']); ?>" required>
                    </div>

                    <!-- Kategori Menu -->
                    <div class="mb-4">
                        <label for="menu_kategori" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
                        <select id="menu_kategori" name="menu_kategori" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Makanan" <?php echo $menu['menu_kategori'] == 'Makanan' ? 'selected' : ''; ?>>Makanan</option>
                            <option value="Minuman" <?php echo $menu['menu_kategori'] == 'Minuman' ? 'selected' : ''; ?>>Minuman</option>
                            <option value="Snack" <?php echo $menu['menu_kategori'] == 'Snack' ? 'selected' : ''; ?>>Snack</option>
                        </select>
                    </div>

                    <!-- Harga Menu -->
                    <div class="mb-4">
                        <label for="menu_harga" class="block text-gray-700 text-sm font-bold mb-2">Harga:</label>
                        <input type="number" id="menu_harga" name="menu_harga" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($menu['menu_harga']); ?>" required min="0">
                    </div>

                    <!-- Gambar Menu -->
                    <div class="mb-4">
                        <label for="menu_gambar" class="block text-gray-700 text-sm font-bold mb-2">Gambar Menu:</label>
                        <input type="file" id="menu_gambar" name="menu_gambar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <?php if (!empty($menu['menu_gambar'])): ?>
                            <img src="<?php echo htmlspecialchars($menu['menu_gambar']); ?>" alt="Gambar Menu" class="mt-2 w-32 h-32 object-contain">
                        <?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>