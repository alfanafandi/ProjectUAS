<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-green-500 to-green-400 flex items-center justify-center min-h-screen text-gray-800">
    <div class="flex flex-col md:flex-row w-full max-w-4xl shadow-lg rounded-lg overflow-hidden bg-white">
        <!-- Left side: Branding dan pesan -->
        <div class="hidden md:flex w-1/2 bg-green-600 items-center justify-center p-10">
            <div class="space-y-4 text-center">
                <!-- Ganti src dengan path logo Anda -->
                <img src="../image/logo.png" alt="Logo" class="h-16 mx-auto">
                <h2 class="text-3xl font-bold text-white">Selamat Datang</h2>
                <p class="text-lg text-white">Layanan terbaik untuk kebutuhan perut Anda.</p>
            </div>
        </div>

        <!-- Right side: Login form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-sm space-y-6">
                <h2 class="text-2xl font-semibold text-center text-gray-800">Login</h2>
                <?php if (isset($error)): ?>
                    <div class="text-red-500 text-center"><?= $error ?></div>
                <?php endif; ?>
                <form action="index.php?modul=login" method="POST" class="space-y-6">
                    <!-- Username Field -->
                    <div>
                        <label for="user_username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" id="user_username" name="user_username" required
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-800">
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="user_password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="user_password" name="user_password" required
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-800">
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full bg-green-500 text-white py-2 rounded-md font-semibold hover:bg-green-600">
                        Log In
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>