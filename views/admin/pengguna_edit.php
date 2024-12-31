<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pengguna</title>
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
            <!-- Formulir Input Pengguna -->
            <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Pengguna</h2>
                <form action="/../../index.php?modul=pengguna&fitur=update&id=<?php echo $user->user_id; ?>" method="POST">
                    <!-- Nama Pengguna -->
                    <div class="mb-4">
                        <label for="pengguna_username" class="block text-gray-700 text-sm font-bold mb-2">Username Pengguna:</label>
                        <input type="text" id="pengguna_username" name="pengguna_username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Nama Pengguna" required>
                    </div>

                    <!-- Kata Sandi -->
                    <div class="mb-4">
                        <label for="pengguna_password" class="block text-gray-700 text-sm font-bold mb-2">Kata Sandi:</label>
                        <input type="password" id="pengguna_password" name="pengguna_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Kata Sandi" required>
                    </div>

                    <!-- Saldo -->
                    <div class="mb-4">
                        <label for="pengguna_saldo" class="block text-gray-700 text-sm font-bold mb-2">Saldo:</label>
                        <input id="pengguna_saldo" name="pengguna_saldo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Saldo" required></input>
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