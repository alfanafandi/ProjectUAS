<?php
// Ambil data dari session
$keranjang = $_SESSION['keranjangItems'] ?? [];
$totalItems = $_SESSION['totalItems'] ?? 0;
$totalPrice = $_SESSION['totalPrice'] ?? 0;
$diskonVoucher = $_SESSION['kode']['diskon'] ?? 0; // Diskon dari kode voucher


require_once __DIR__ . '/../../model/voucher_model.php';
$modelVoucher = new VoucherModel();
$vouchers = $modelVoucher->getAllVouchers();
$validDiskonCodes = [];
foreach ($vouchers as $voucher) {
    $validDiskonCodes[$voucher->kode] = (int)$voucher->diskon;
}


require_once __DIR__ . '/../../model/diskon_model.php';
$modelDiskon = new DiskonModel();
$diskonRestoran = $modelDiskon->getAllDiskons();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="icon" href="image\logo.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <header class="sticky top-0 bg-white shadow-md z-10">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
    </header>
    <main class="px-20">
        <!-- Bagian Keranjang -->
        <section class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h1 class="text-2xl font-bold mb-4 text-center">Keranjang Belanja</h1>
            <div id="keranjang-container" class="max-w-2xl mx-auto">
                <!-- Isi keranjang akan ditampilkan di sini -->
            </div>


            <!-- Menu Voucher -->
            <div class="bg-gray-50 p-4 rounded-lg mt-6 mx-auto max-w-md text-center">
                <div class="flex items-center gap-4">
                    <input type="text" id="diskon-input" class="border p-2 flex-1 rounded-lg" placeholder="Masukkan kode diskon" />
                    <button id="apply-diskon-btn" class="bg-blue-500 text-white py-2 px-6 rounded-md font-semibold">Terapkan</button>
                </div>
                <p id="diskon-info" class="text-green-500 mt-2 hidden">Voucher berhasil diterapkan!</p>
                <p id="error-info" class="text-red-500 mt-2 hidden">Kode Voucher tidak valid.</p>
            </div>


            <!-- Diskon Restoran -->
            <div class="bg-gray-50 p-4 rounded-lg mt-6 mx-auto max-w-md text-center">
                <div id="diskon-restoran-container" class="grid grid-cols-1 gap-4">
                    <!-- Diskon restoran akan dimuat dari data JSON -->
                </div>
            </div>

            <!-- Total Harga -->
            <div id="total-harga-container" class="text-right font-bold text-lg mt-6 mx-auto max-w-md text-center"></div>

            <!-- Tombol Checkout -->
            <div class="mt-12 flex justify-center">
                <button id="checkout-btn" class="bg-green-500 text-white py-3 px-8 rounded-md font-semibold shadow-md hover:bg-green-600 transition-all">
                    Checkout
                </button>
            </div>
        </section>
    </main>

    <script>
        // Data dari PHP
        const keranjang = <?php echo json_encode($keranjang, JSON_HEX_TAG); ?>;
        const validDiskonCodes = <?php echo json_encode($validDiskonCodes, JSON_HEX_TAG); ?>;
        const diskonRestoranData = <?php echo json_encode($diskonRestoran, JSON_HEX_TAG); ?>;
        let diskonVoucher = <?php echo $diskonVoucher; ?>;
        let diskonRestoran = 0; // Awalnya 0

        // Elemen HTML
        const keranjangContainer = document.getElementById('keranjang-container');
        const diskonInput = document.getElementById('diskon-input');
        const applyDiskonBtn = document.getElementById('apply-diskon-btn');
        const diskonInfo = document.getElementById('diskon-info');
        const errorInfo = document.getElementById('error-info');
        const totalHargaContainer = document.getElementById('total-harga-container');
        const diskonRestoranContainer = document.getElementById('diskon-restoran-container');
        const checkoutBtn = document.getElementById('checkout-btn');

        let totalHarga = 0;
        let totalSetelahDiskon;

        function renderKeranjang() {
            if (keranjang.length === 0) {
                keranjangContainer.innerHTML = '<p class="text-gray-500 text-center">Keranjang Anda kosong. Silakan tambahkan item terlebih dahulu.</p>';
                checkoutBtn.style.display = 'none';
                totalHargaContainer.textContent = '';
                return;
            }

            checkoutBtn.style.display = 'block';
            totalHarga = 0;

            keranjangContainer.innerHTML = keranjang.map((item, index) => {
                const subtotal = item.price * item.quantity;
                totalHarga += subtotal;

                return `
            <div class="flex items-center justify-between bg-white p-4 mb-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="text-lg font-semibold text-gray-800">${item.name}</div>
                </div>
                <div>
                    <p class="text-gray-600">Harga: <span class="font-semibold">Rp ${item.price.toLocaleString('id-ID')}</span></p>
                    <p class="text-gray-600">Jumlah: <span class="font-semibold">${item.quantity}</span></p>
                </div>
                <div class="flex space-x-2">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition" onclick="ubahJumlah(${index}, 1)">+</button>
                    <button class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition" onclick="ubahJumlah(${index}, -1)">-</button>
                </div>
            </div>
        `;
            }).join('');

            updateTotalHarga();
        }


        // Fungsi untuk render diskon restoran
        function renderDiskonRestoran() {
            const restoranIds = [...new Set(keranjang.map(item => String(item.restoran_id)))];
            const diskonTersedia = diskonRestoranData.filter(diskon =>
                restoranIds.includes(String(diskon.restoran_id)) // Pastikan tipe data sama
            );


            if (diskonTersedia.length === 0) {
                diskonRestoranContainer.innerHTML = '<p class="text-gray-500">Tidak ada diskon yang tersedia untuk restoran di keranjang Anda.</p>';
                return;
            }

            diskonRestoranContainer.innerHTML = diskonTersedia.map(diskon =>
                `<button class="diskon-btn bg-green-500 text-white py-2 px-4 rounded-md" data-diskon="${diskon.diskon_presentase}">
                    ${diskon.restoran_nama}: ${diskon.diskon_nama} (${diskon.diskon_presentase}%)
                </button>`
            ).join('');

            document.querySelectorAll('.diskon-btn').forEach(button => {
                button.addEventListener('click', pilihDiskon);
            });
        }

        // Fungsi untuk update total harga
        function updateTotalHarga() {
            let totalDiskon = diskonVoucher + diskonRestoran;
            if (totalDiskon > 100) {
                totalDiskon = 100;
            }
            totalSetelahDiskon = totalHarga * (1 - totalDiskon / 100);

            totalHargaContainer.innerHTML = `
        <div class="bg-gray-100 p-4 rounded-lg shadow-md mt-4">
            ${diskonVoucher ? `
                <div class="flex justify-between mb-2">
                    <span>Voucher</span>
                    <span>-${diskonVoucher}%</span>
                </div>
            ` : ''}
            ${diskonRestoran ? `
                <div class="flex justify-between mb-2">
                    <span>Diskon Restoran</span>
                    <span>-${diskonRestoran}%</span>
                </div>
            ` : ''}
            <hr class="my-2">
            <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span>Rp ${totalSetelahDiskon.toLocaleString('id-ID')}</span>
            </div>
        </div>
    `;
        }



        // Fungsi untuk memilih diskon restoran
        function pilihDiskon(event) {
            diskonRestoran = parseInt(event.target.getAttribute('data-diskon'));
            updateTotalHarga();
        }

        // Fungsi untuk menerapkan kode voucher
        function applyDiskon() {
            const kodeDiskon = diskonInput.value.trim().toUpperCase();

            if (validDiskonCodes[kodeDiskon]) {
                diskonVoucher = parseInt(validDiskonCodes[kodeDiskon], 10);
                updateTotalHarga();

                diskonInfo.classList.remove('hidden');
                errorInfo.classList.add('hidden');
            } else {
                errorInfo.classList.remove('hidden');
                diskonInfo.classList.add('hidden');
            }
        }

        // Fungsi untuk mengubah jumlah item di keranjang
        function ubahJumlah(index, perubahan) {
            keranjang[index].quantity += perubahan;

            if (keranjang[index].quantity <= 0) {
                keranjang.splice(index, 1);
            }

            renderKeranjang();
        }

        // Event listener untuk tombol apply voucher
        applyDiskonBtn.addEventListener('click', applyDiskon);
        checkoutBtn.addEventListener('click', () => {
            if (keranjang.length > 0) {
                let totalDiskon = diskonVoucher + diskonRestoran;
                totalSetelahDiskon = totalHarga - (totalHarga * totalDiskon / 100);

                if (confirm('Apakah Anda yakin ingin melakukan checkout?')) {
                    fetch('index.php?modul=checkout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                keranjangItems: keranjang,
                                totalItems: keranjang.length,
                                totalPrice: totalSetelahDiskon,
                                diskon: totalDiskon
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Update session saldo
                                <?php $_SESSION['saldo'] = $objectPengguna->getSaldoLoggedInUser(); ?>
                                window.location.href = 'index.php?modul=customer_dashboard';
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            } else {
                alert('Keranjang Anda kosong. Silakan tambahkan item sebelum checkout.');
            }
        });

        renderKeranjang();
        renderDiskonRestoran();
    </script>
</body>

</html>