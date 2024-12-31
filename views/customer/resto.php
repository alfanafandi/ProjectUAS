<?php
$keranjang = $_SESSION['keranjangItems'] ?? [];
$totalPrice = $_SESSION['totalPrice'] ?? 0;

$diskonRestoran = [];
$jsonFilePathRestoran = __DIR__ . '/../../json/diskons.json';
if (file_exists($jsonFilePathRestoran)) {
    $jsonDataRestoran = json_decode(file_get_contents($jsonFilePathRestoran), true);
    foreach ($jsonDataRestoran['diskons'] ?? [] as $diskon) {
        $diskonRestoran[] = [
            'restoran_id' => $diskon['diskon_restoran']['restoran_id'] ?? null,
            'restoran_nama' => $diskon['diskon_restoran']['restoran_nama'] ?? 'Nama restoran tidak tersedia',
            'diskon_nama' => $diskon['diskon_nama'] ?? 'Diskon tidak diketahui',
            'diskon_presentase' => $diskon['diskon_presentase'] ?? 0,
        ];
    }
}
$restoran_id = $restoran->restoran_id;

$diskonRestoranFiltered = array_filter($diskonRestoran, function ($diskon) use ($restoran_id) {
    return $diskon['restoran_id'] === $restoran_id;
});

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($restoran->restoran_nama); ?> - Restoo</title>
    <link rel="icon" href="../image/logo.png" type="image/x-icon">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #keranjang-bar-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 0;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        #keranjang-bar {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            transform: translateY(100%);
            display: none;
            opacity: 0;
            background-color: #28a745;
            border-radius: 10px;
            padding: 10px 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #keranjang-bar.visible {
            transform: translateY(0%);
            display: flex;
            opacity: 1;
        }

        #keranjang-info {
            font-size: 1.2rem;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen m-0 p-0">
    <!-- Navbar -->
    <header class="sticky top-0 bg-white z-10">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
    </header>

    <main class="p-0 m-0 pb-20">
        <section class="bg-white p-6 rounded-lg shadow-md mb-6 flex justify-center">
            <div class="flex flex-col items-center gap-4">
                <img src="<?= htmlspecialchars($restoran->restoran_gambar); ?>" alt="<?= htmlspecialchars($restoran->restoran_nama); ?>" class="w-24 h-24 rounded-lg">
                <h2 class="text-xl font-bold text-center"><?= htmlspecialchars($restoran->restoran_nama); ?></h2>
            </div>
        </section>

        <section class="bg-white p-4 rounded-lg shadow-md mb-6 flex justify-center">
            <div class="flex flex-col gap-3 w-full">
                <?php if (!empty($diskonRestoranFiltered)): ?>
                    <?php foreach ($diskonRestoranFiltered as $diskon): ?>
                        <div class="bg-yellow-100 p-3 rounded-md shadow-sm">
                            <p class="font-semibold text-yellow-600 text-l text-center"><?= htmlspecialchars($diskon['diskon_nama']); ?></p>
                            <p class="text-l text-gray-500 text-center">
                                Diskon <?= htmlspecialchars($diskon['diskon_presentase']); ?>% di
                                <?= htmlspecialchars($diskon['restoran_nama']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-xs text-gray-500 text-center">Tidak ada diskon untuk restoran ini.</p>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach ($menus_by_restoran as $menu): ?>
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <img src="<?= htmlspecialchars($menu->menu_gambar); ?>" alt="<?= htmlspecialchars($menu->menu_nama); ?>" class="w-full h-32 object-contain rounded-lg mb-2">
                        <h4 class="font-bold text-md"><?= htmlspecialchars($menu->menu_nama); ?></h4>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-green-500 font-bold">Rp <?= number_format($menu->menu_harga, 0, ',', '.'); ?></span>
                            <button class="bg-blue-500 text-white py-1 px-4 rounded-md btn-tambah"
                                data-id="<?= $menu->menu_id; ?>"
                                data-item="<?= htmlspecialchars($menu->menu_nama); ?>"
                                data-price="<?= $menu->menu_harga; ?>"
                                data-restoran="<?= $restoran->restoran_id; ?>">Tambah</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <div id="keranjang-bar-wrapper">
        <div id="keranjang-bar" class="text-white px-8 py-3 hidden flex justify-center items-center cursor-pointer">
            <span id="keranjang-info" class="text-l font-semibold">0 Item - Rp 0</span>
        </div>
    </div>

    <script>
        let keranjangItems = [];

        document.querySelectorAll('.btn-tambah').forEach(button => {
            button.addEventListener('click', function() {
                const itemName = this.getAttribute('data-item');
                const itemPrice = parseInt(this.getAttribute('data-price'));
                const itemId = this.getAttribute('data-id');
                const restoranId = this.getAttribute('data-restoran');

                const existingItemIndex = keranjangItems.findIndex(item => item.id === itemId && item.restoran_id === restoranId);

                if (existingItemIndex >= 0) {
                    keranjangItems[existingItemIndex].quantity++;
                } else {
                    keranjangItems.push({
                        id: itemId,
                        name: itemName,
                        price: itemPrice,
                        quantity: 1,
                        restoran_id: restoranId
                    });
                }

                let totalItems = keranjangItems.reduce((acc, item) => acc + item.quantity, 0);
                let totalPrice = keranjangItems.reduce((acc, item) => acc + item.price * item.quantity, 0);

                document.getElementById('keranjang-info').innerText = `${totalItems} Item - Rp ${totalPrice.toLocaleString('id-ID')}`;

                const keranjangBar = document.getElementById('keranjang-bar');
                keranjangBar.classList.add('visible');
                keranjangBar.style.display = 'flex';
            });
        });

        document.getElementById('keranjang-bar').addEventListener('click', function() {
            fetch('index.php?modul=simpan_keranjang', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        keranjangItems: keranjangItems,
                        totalItems: keranjangItems.reduce((acc, item) => acc + item.quantity, 0),
                        totalPrice: keranjangItems.reduce((acc, item) => acc + item.price * item.quantity, 0)
                    })
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/index.php?modul=keranjang';
                    } else {
                        return response.json().then(err => {
                            console.error('Error:', err.message);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>