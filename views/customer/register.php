<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="image\logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .background-image {
            background-image: url('/image/backgroundLogin.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="background-image flex items-center justify-center min-h-screen text-gray-800">
    <div class="flex flex-col w-full max-w-md shadow-lg rounded-lg overflow-hidden bg-white bg-opacity-90">
        <!-- Right side: Register form -->
        <div class="w-full flex items-center justify-center p-8">
            <div class="w-full max-w-sm space-y-6">
                <h2 class="text-3xl font-semibold text-center text-gray-800">Register</h2>
                <?php if (isset($error)): ?>
                    <div class="text-red-500 text-center"><?= $error ?></div>
                <?php endif; ?>
                <form action="index.php?modul=register" method="POST" class="space-y-6">
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

                    <!-- Register Button -->
                    <button type="submit"
                        class="w-full bg-green-500 text-white py-2 rounded-md font-semibold hover:bg-green-600 transition duration-300">
                        Register
                    </button>
                </form>
                <div class="text-center">
                    <a href="index.php?modul=login" class="text-green-500 hover:underline">Sudah punya akun? Login di sini</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>