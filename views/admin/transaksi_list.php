<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-gray-100 via-gray-200 to-gray-300 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
    <?php include __DIR__ . '/../includes/navbar_admin.php'; ?>

    <!-- Main container -->
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ .  '/../includes/sidebar_admin.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Your main content goes here -->
            <div class="container mx-auto">
                <!-- Transactions Table -->
                <div class="bg-white shadow-md rounded my-6 overflow-hidden">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gradient-to-r from-gray-800 to-gray-700 text-white">
                            <tr>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Urutan</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Nama Pengguna</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Jumlah Top-Up</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php
                            if (!empty($transaksis)) {
                                $queue = 1;
                                foreach (array_reverse($transaksis) as $transaction) { ?>
                                    <tr class="text-center border-b border-gray-300 transition duration-200 ease-in-out hover:bg-gray-200">
                                        <td class="py-3 px-4 text-blue-600"><?php echo $queue++; ?></td>
                                        <td class="py-3 px-4">
                                            <?php
                                            if (isset($transaction->nama_pengguna) && $transaction->nama_pengguna != null) {
                                                echo htmlspecialchars($transaction->nama_pengguna);
                                            } else {
                                                echo 'Nama Pengguna Tidak Ditemukan';
                                            }
                                            ?>
                                        </td>
                                        <td class="py-3 px-4">Rp<?php echo number_format($transaction->jumlah_topup, 0, ',', '.'); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($transaction->status); ?></td>
                                        <td class="py-3 px-4">
                                            <?php if ($transaction->status === 'Pending') { ?>
                                                <button class="bg-green-200 hover:bg-green-300 text-green-700 font-semibold py-1 px-3 rounded-md transition duration-200">
                                                    <a href="index.php?modul=transaksi&fitur=approved&id=<?php echo $transaction->transaksi_id; ?>" class="block">Konfirmasi</a>
                                                </button>
                                            <?php } ?>
                                            <button class="bg-red-200 hover:bg-red-300 text-red-700 font-semibold py-1 px-3 rounded-md transition duration-200">
                                                <a href="index.php?modul=transaksi&fitur=delete&id=<?php echo $transaction->transaksi_id; ?>" class="block" onclick="return confirm('Apakah anda yakin ingin menghapus transaksi ini?');">Delete</a>
                                            </button>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="5" class="py-3 px-4 text-center">Tidak ada data transaksi tersedia.</td>
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