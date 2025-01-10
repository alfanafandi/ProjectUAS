<?php
$keranjang = $_SESSION['keranjangItems'] ?? [];
$totalPrice = $_SESSION['totalPrice'] ?? 0;

$restoran_id = $restoran->restoran_id;

// Fetch discount data from the database
require_once __DIR__ . '/../../model/diskon_model.php';
$modelDiskon = new DiskonModel();
$diskonRestoranFiltered = $modelDiskon->getDiskonsByRestoran($restoran_id);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($restoran->restoran_nama); ?> - Restoo</title>
    <link rel="icon" href="image\logo.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #keranjang-bar-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: white;
            display: none;
            /* Initially hidden */
            justify-content: center;
            align-items: center;
            padding: 5px 0;
            /* Adjust padding to make it flatter */
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        #keranjang-bar-wrapper.visible {
            display: flex;
            /* Show when visible class is added */
        }

        #keranjang-bar {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            transform: translateY(100%);
            display: none;
            opacity: 0;
            background-color: #28a745;
            width: 50%;
            /* Make the cart bar wider */
            border-radius: 50px;
            /* Round the edges more */
            padding: 5px 20px;
            height: 40px;
            /* Adjust padding to make it flatter */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #keranjang-bar.visible {
            transform: translateY(0%);
            display: flex;
            opacity: 1;
        }

        #keranjang-info {
            font-size: 0.9rem;
            font-weight: bold;
            flex-grow: 1;
        }

        #keranjang-bar .restoran-name {
            font-size: 0.9rem;
            font-weight: normal;
            color: #ffffff;
        }

        #keranjang-bar .shopping-bag-icon {
            width: 24px;
            height: 24px;
            background-image: url('https://cdn-icons-png.flaticon.com/512/1170/1170678.png');
            /* Use a different icon library */
            background-size: contain;
            background-repeat: no-repeat;
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
                        <img src="<?= htmlspecialchars($menu['menu_gambar']); ?>" alt="<?= htmlspecialchars($menu['menu_nama']); ?>" class="w-full h-32 object-contain rounded-lg mb-2">
                        <h4 class="font-bold text-md"><?= htmlspecialchars($menu['menu_nama']); ?></h4>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-green-500 font-bold">Rp <?= number_format($menu['menu_harga'], 0, ',', '.'); ?></span>
                            <button class="bg-blue-500 text-white py-1 px-4 rounded-md btn-tambah"
                                data-id="<?= $menu['menu_id']; ?>"
                                data-item="<?= htmlspecialchars($menu['menu_nama']); ?>"
                                data-price="<?= $menu['menu_harga']; ?>"
                                data-restoran="<?= $restoran->restoran_id; ?>">Tambah</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <div id="keranjang-bar-wrapper">
        <div id="keranjang-bar" class="text-white px-8 py-3 hidden flex justify-between items-center cursor-pointer">
            <div>
                <span id="keranjang-info" class="text-l font-semibold">0 Item</span>
                <div class="restoran-name"><?= htmlspecialchars($restoran->restoran_nama); ?></div>
            </div>
            <div class="flex items-center">
                <span id="keranjang-price" class="text-l font-semibold">Rp 0</span>
                <span class="shopping-bag-icon ml-2"></span>
            </div>
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

                document.getElementById('keranjang-info').innerText = `${totalItems} Item`;
                document.getElementById('keranjang-price').innerText = `Rp ${totalPrice.toLocaleString('id-ID')}`;

                const keranjangBar = document.getElementById('keranjang-bar');
                keranjangBar.classList.add('visible');
                keranjangBar.style.display = 'flex';

                const keranjangWrapper = document.getElementById('keranjang-bar-wrapper');
                keranjangWrapper.classList.add('visible');
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
                        window.location.href = 'index.php?modul=keranjang';
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