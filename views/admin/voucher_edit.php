<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Voucher</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
    <?php include __DIR__ . '/../includes/navbar_admin.php'; ?>

    <!-- Main container -->
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../includes/sidebar_admin.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Formulir Input Voucher -->
            <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Voucher</h2>
                <form action="/../../index.php?modul=voucher&fitur=update&id=<?php echo $voucher->voucher_id; ?>" method="POST">
                    <!-- Kode Voucher -->
                    <div class="mb-4">
                        <label for="voucher_kode" class="block text-gray-700 text-sm font-bold mb-2">Kode Voucher:</label>
                        <input type="text" id="voucher_kode" name="voucher_kode" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Kode Voucher" required>
                    </div>

                    <!-- Diskon (%) -->
                    <div class="mb-4">
                        <label for="discount" class="block text-gray-700 text-sm font-bold mb-2">Diskon (%):</label>
                        <input type="number" id="discount" name="discount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Persentase Diskon" required min="0" max="100">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Tambah Voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>