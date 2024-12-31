<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Restoran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-gray-100 via-gray-200 to-gray-300 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
    <?php include __DIR__ . '/../includes/navbar_admin.php'; ?>

    <!-- Main container -->
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../includes/sidebar_admin.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Your main content goes here -->
            <div class="container mx-auto">
                <!-- Button to Insert New Restoran -->
                <div class="mb-4">
                    <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-md transition duration-200 mb-4">
                        <a href="views/admin/restoran_input.php">Insert New Restoran</a>
                    </button>
                </div>

                <!-- Restoran Table -->
                <div class="bg-white shadow-md rounded my-6 overflow-hidden">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gradient-to-r from-gray-800 to-gray-700 text-white">
                            <tr>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Restoran ID</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Restoran Name</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php if (!empty($restorans)) {
                                foreach ($restorans as $restoran) { ?>
                                    <tr class="text-center border-b border-gray-300 transition duration-200 ease-in-out hover:bg-gray-200">
                                        <td class="py-3 px-4 text-blue-600"><?php echo htmlspecialchars($restoran->restoran_id); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($restoran->restoran_nama); ?></td>
                                        <td class="py-3 px-4">
                                            <button class="bg-green-200 hover:bg-green-300 text-green-700 font-semibold py-1 px-3 rounded-md transition duration-200">
                                                <a href="index.php?modul=restoran&fitur=edit&id=<?php echo $restoran->restoran_id; ?>" class="block">Update</a>
                                            </button>
                                            <button class="bg-red-200 hover:bg-red-300 text-red-700 font-semibold py-1 px-3 rounded-md transition duration-200">
                                                <a href="index.php?modul=restoran&fitur=delete&id=<?php echo $restoran->restoran_id; ?>" class="block" onclick="return confirm('Apakah anda yakin ingin menghapus restoran ini?');">Delete</a>
                                            </button>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="5" class="py-3 px-4 text-center">Tidak ada data restoran tersedia.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>