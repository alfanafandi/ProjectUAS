<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoo</title>
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen m-0 p-0">
    <!-- Navbar -->
    <header class="sticky top-0 bg-white z-10">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
    </header>

    <!-- Kontainer Utama -->
    <div class="w-screen p-0 m-0">
        <!-- Saldo dan Promo -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6 w-full">
            <div class="bg-gradient-to-r from-teal-400 to-teal-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-white">Saldo Anda</h2>
                        <p class="text-white text-xl font-bold"><?php echo "Rp " . number_format($saldo, 0, ',', '.'); ?></p>
                    </div>
                    <a href="/index.php?modul=saldo" class="bg-white text-teal-500 text-l py-2 px-4 rounded-lg shadow-md hover:bg-gray-200 transition">Isi Saldo</a>
                </div>
            </div>
            <!-- Promo Hari Ini -->
            <div class="mt-4">
                <h3 class="text-s font-semibold text-gray-800">Promo Hari Ini</h3>
                <div class="flex gap-4 overflow-x-auto mt-1">
                    <?php if (!empty($vouchers)) : ?>
                        <?php foreach ($vouchers as $voucher) : ?>
                            <div class="bg-gradient-to-r from-green-400 to-green-500 p-4 rounded-lg text-white shadow-sm min-w-[160px] w-full flex items-center justify-between">
                                <p class="text-gray-800">Kode : <?php echo $voucher->kode; ?></p> <!-- Teks sejajar kiri -->
                                <p class="text-gray-800 mt-1"><?php echo $voucher->diskon ?>% Diskon</p> <!-- Teks sejajar kanan -->
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-gray-500">Tidak ada promo saat ini.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-3">Restoran Pilihan</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php if (!empty($restorans)) : ?>
                        <?php foreach ($restorans as $restoran) : ?>
                            <a href="index.php?modul=belanja&id=<?php echo $restoran->restoran_id; ?>" class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition max-w-xs mx-auto">
                                <img src="<?php echo $restoran->restoran_gambar; ?>" alt="<?php echo htmlspecialchars($restoran->restoran_nama); ?>" class="w-full h-32 object-cover rounded-lg mb-2">
                                <h4 class="text-md font-bold"><?php echo htmlspecialchars($restoran->restoran_nama); ?></h4>
                                <script>
                                    console.log("Image path: <?php echo $restoran->restoran_gambar; ?>");
                                </script>
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-gray-500">Belum ada restoran tersedia.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</body>

</html>