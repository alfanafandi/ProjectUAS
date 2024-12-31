<?php
session_start();
// Dependensi
require_once 'controller/controller_restoran.php';
require_once 'controller/controller_menu.php';
require_once 'controller/controller_pengguna.php';
require_once 'controller/controller_transaksi.php';
require_once 'controller/controller_voucher.php';
require_once 'controller/controller_diskon.php';



// Objek sebagai parameter
$modelRestoran = new RestoranModel();
$modelMenu = new MenuModel($modelRestoran);
$modelPengguna = new PenggunaModel();
$modelTransaksi = new TransaksiModel($modelPengguna);
$modelVoucher = new VoucherModel();
$modelDiskon = new DiskonModel($modelRestoran);

// Objek controller
$objectRestoran = new controllerRestoran();
$objectMenu = new controllerMenu($modelRestoran);
$objectPengguna = new controllerPengguna();
$objectTransaksi = new controllerTransaksi($modelPengguna);
$objectVoucher = new controllerVoucher();
$objectDiskon = new controllerDiskon($modelRestoran);

if (!isset($_SESSION['user_id']) && (!isset($_SESSION['restoran_id'])) && (!isset($_GET['modul']) || $_GET['modul'] != 'login')) {
    header('Location: index.php?modul=login');
    exit;
}

$modul = $_GET['modul'] ?? 'dashboard';

switch ($modul) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['user_username'];
            $password = $_POST['user_password'];

            // Verifikasi restoran
            $restoran = $modelRestoran->getRestoranByName($username);
            if ($restoran && $password === $restoran->restoran_password) {
                $_SESSION['restoran_id'] = $restoran->restoran_id;
                $_SESSION['username'] = $restoran->restoran_nama;

                header('Location: index.php?modul=restoran_dashboard');
                exit;
            }

            // Verifikasi user lain
            $user = $modelPengguna->getUserByUsername($username);
            if ($user && $password === $user->user_password) {
                $_SESSION['user_id'] = $user->user_id;
                $_SESSION['username'] = $user->user_username;

                switch ($user->user_username) {
                    case 'admin':
                        header('Location: index.php?modul=dashboard');
                        break;
                    default:
                        header('Location: index.php?modul=customer_dashboard');
                        break;
                }
                exit;
            } else {
                $error = "Username atau password salah!";
            }
        }
        include 'views/login.php';
        break;

    case 'logout':
        session_unset();
        session_destroy();
        header('Location: index.php?modul=login');
        exit;

    case 'dashboard':
        $restorans = $modelRestoran->getAllRestorans();
        $menus = $modelMenu->getAllMenus();
        $transaksis = $modelTransaksi->getAllTransaksi();
        $vouchers = $modelVoucher->getAllVouchers();
        $diskons = $modelDiskon->getAllDiskons();
        $penggunas = $modelPengguna->getAllUsers();

        $totalTransaksi = count($transaksis);
        $totalPengguna = count($penggunas);
        $totalRestoran = count($restorans);

        if (isset($_SESSION['saldo'])) {
            $saldo = $_SESSION['saldo'];
        }
        include 'views/admin/admin_dashboard.php';
        break;

    case 'restoran_dashboard':
        if (!isset($_SESSION['restoran_id'])) {
            header('Location: index.php?modul=login');
            exit;
        }

        $restoran_id = $_SESSION['restoran_id'];
        $restoran = $modelRestoran->getRestoranById($restoran_id);
        $menuRestoran = $objectMenu->getMenusByRestoran($restoran_id);
        $totalMenu = count($menuRestoran);

        include 'views/restoran/restoran_dashboard.php';
        break;

    case 'customer_dashboard':
        $restorans = $modelRestoran->getAllRestorans();
        $menus = $modelMenu->getAllMenus();
        $transaksis = $modelTransaksi->getAllTransaksi();
        $vouchers = $modelVoucher->getAllVouchers();
        $diskons = $modelDiskon->getAllDiskons();
        $saldo = $objectPengguna->getSaldoLoggedInUser();
        include 'views/customer/customer_dashboard.php';
        break;

    case 'restoran':
        $fitur = $_GET['fitur'] ?? null;
        $id = $_GET['id'] ?? null;
        switch ($fitur) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $nama = $_POST['restoran_nama'];
                    $password = $_POST['restoran_password'];

                    if (isset($_FILES['restoran_gambar']) && $_FILES['restoran_gambar']['error'] == UPLOAD_ERR_OK) {
                        $uploadDir = 'uploads/restorans/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        $gambarName = basename($_FILES['restoran_gambar']['name']);
                        $uploadFile = $uploadDir . uniqid() . '_' . $gambarName;

                        if (move_uploaded_file($_FILES['restoran_gambar']['tmp_name'], $uploadFile)) {
                            $objectRestoran->addRestoran($nama, $password, $uploadFile);
                            header('Location: index.php?modul=restoran');
                        } else {
                            throw new Exception('Gagal mengunggah file gambar.');
                        }
                    } else {
                        throw new Exception('Gambar restoran wajib diunggah.');
                    }
                } else {
                    include 'views/restoran_input.php';
                }
                break;

            case 'edit':
                $objectRestoran->editById($id);
                break;

            case 'update':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $nama = $_POST['restoran_nama'];
                    $password = $_POST['restoran_password'];

                    if (isset($_FILES['restoran_gambar']) && $_FILES['restoran_gambar']['error'] == UPLOAD_ERR_OK) {
                        $uploadDir = 'uploads/restorans/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        $gambarName = basename($_FILES['restoran_gambar']['name']);
                        $uploadFile = $uploadDir . uniqid() . '_' . $gambarName;

                        if (move_uploaded_file($_FILES['restoran_gambar']['tmp_name'], $uploadFile)) {
                            $objectRestoran->updateRestoran($id, $nama, $password, $uploadFile);
                        } else {
                            throw new Exception('Gagal mengunggah file gambar.');
                        }
                    } else {
                        $restoran = $objectRestoran->getRestoranById($id);
                        if ($restoran) {
                            $objectRestoran->updateRestoran($id, $nama, $password, $restoran->restoran_gambar);
                        } else {
                            throw new Exception('Restoran tidak ditemukan.');
                        }
                    }
                }
                break;

            case 'delete':
                $objectRestoran->deleteRestoran($id);
                break;

            default:
                $objectRestoran->listRestorans();
        }
        break;

    case 'menu':
        $fitur = $_GET['fitur'] ?? null;
        $id = $_GET['id'] ?? null;
        switch ($fitur) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $resto = $_POST['menu_restoran'];
                    $nama = $_POST['menu_nama'];
                    $harga = $_POST['menu_harga'];
                    $kategori = $_POST['menu_kategori'];
                    $objectMenu->addMenu($resto, $nama, $kategori, $harga);
                } else {
                    include 'views/menu_input.php';
                }
                break;
            case 'edit':
                $objectMenu->editById($id);
                break;
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $resto = $_POST['menu_restoran'];
                    $nama = $_POST['menu_nama'];
                    $harga = $_POST['menu_harga'];
                    $kategori = $_POST['menu_kategori'];
                    $objectMenu->updateMenu($id, $resto, $nama, $kategori, $harga);
                }
                break;
            case 'delete':
                $objectMenu->deleteMenu($id);
                break;
            default:
                $restoran_id = $_SESSION['restoran_id'];
                $restoran = $modelRestoran->getRestoranById($restoran_id);
                $menuRestoran = $objectMenu->getMenusByRestoran($restoran_id);
                include 'views/restoran/menu_list.php';
        }
        break;

    case 'pengguna':
        $fitur = $_GET['fitur'] ?? null;
        $id = $_GET['id'] ?? null;
        switch ($fitur) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $username = $_POST['pengguna_username'];
                    $password = $_POST['pengguna_password'];
                    $saldo = isset($_POST['pengguna_saldo']) ? intval($_POST['pengguna_saldo']) : 0;
                    $objectPengguna->addPengguna($username, $password, $saldo);
                } else {
                    include 'views/admin/pengguna_input.php';
                }
                break;
            case 'edit':
                $objectPengguna->editById($id);
                break;
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $username = $_POST['pengguna_username'];
                    $password = password_hash($_POST['pengguna_password'], PASSWORD_DEFAULT);
                    $saldo = isset($_POST['pengguna_saldo']) ? intval($_POST['pengguna_saldo']) : 0;
                    $objectPengguna->updatePengguna($id, $username, $password, $saldo);
                }
                break;
            case 'delete':
                $objectPengguna->deletePengguna($id);
                break;
            default:
                $objectPengguna->listPengguna();
        }
        break;

    case 'transaksi':
        $fitur = $_GET['fitur'] ?? null;
        $id = $_GET['id'] ?? null;

        switch ($fitur) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $pengguna_id = filter_input(INPUT_POST, 'pengguna', FILTER_SANITIZE_NUMBER_INT);
                    $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT);

                    if ($jumlah === false || $jumlah <= 0) {
                        die('Jumlah tidak valid. Harus berupa bilangan bulat positif.');
                    }

                    // Cari pengguna berdasarkan ID
                    $pengguna = $objectPengguna->getUserById($pengguna_id);
                    if ($pengguna) {
                        $objectTransaksi->addTransaksi($pengguna, $jumlah);
                        exit;
                    } else {
                        die('Pengguna tidak ditemukan.');
                    }
                } else {
                    include 'views/customer/isi_saldo.php';
                }
                break;

            case 'saldo':
                // Tampilkan daftar transaksi pengguna
                $objectTransaksi->listTransaksi();
                break;

            case 'approved':
                if ($id) {
                    $objectTransaksi->approveTransaksi((int)$id);
                } else {
                    header('Location: index.php?modul=transaksi&error=missing_id');
                }
                break;

            case 'delete':
                if ($id) {
                    $objectTransaksi->deleteTransaksi((int)$id);
                } else {
                    header('Location: index.php?modul=transaksi&error=missing_id');
                }
                break;

            default:
                $objectTransaksi->listTransaksi();
        }
        break;

    case 'voucher':
        $fitur = $_GET['fitur'] ?? null;
        $id = $_GET['id'] ?? null;
        switch ($fitur) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $kode = $_POST['voucher_kode'];
                    $diskon = $_POST['discount'];
                    $objectVoucher->addVoucher($kode, $diskon);
                } else {
                    include 'views/admin/voucher_input.php';
                }
                break;
            case 'edit':
                $objectVoucher->editById($id);
                break;
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $kode = $_POST['voucher_kode'];
                    $diskon = $_POST['discount'];
                    $objectVoucher->updateVoucher($id, $kode, $diskon);
                }
                break;
            case 'delete':
                $objectVoucher->deleteVoucher($id);
                break;
            default:
                $objectVoucher->listVouchers();
        }
        break;

    case 'diskon':
        $fitur = $_GET['fitur'] ?? null;
        $id = $_GET['id'] ?? null;
        switch ($fitur) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $resto = $_POST['diskon_restoran'];
                    $nama = $_POST['diskon_nama'];
                    $persen = $_POST['diskon_persen'];
                    $objectDiskon->addDiskon($resto, $nama, $persen);
                } else {
                    include 'views/restoran/diskon_input.php';
                }
                break;
            case 'edit':
                $objectDiskon->editById($id);
                break;
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $resto = $_POST['diskon_restoran'];
                    $nama = $_POST['diskon_nama'];
                    $persen = $_POST['diskon_persen'];
                    $objectDiskon->updateDiskon($id, $resto, $nama, $persen);
                }
                break;
            case 'delete':
                $objectDiskon->deleteDiskon($id);
                break;
            default:
                $restoran_id = $_SESSION['restoran_id'];
                $restoran = $modelRestoran->getRestoranById($restoran_id);
                $objectDiskon->listDiskonsByRestoran($restoran_id);
        }
        break;

    case 'saldo':
        $fitur = $_GET['fitur'] ?? null;
        $id = $_GET['id'] ?? null;
        switch ($fitur) {
            default:
                include 'views/customer/isi_saldo.php';
        }
        break;

    case 'belanja':
        $fitur = $_GET['fitur'] ?? null;
        $id = $_GET['id'] ?? null;
        switch ($fitur) {
            default:
                $objectRestoran->belanjaById($id);
        }
        break;

    case 'rekomendasi':
        $fitur = $_GET['fitur'] ?? null;
        $id = $_GET['id'] ?? null;
        switch ($fitur) {
            default:
                $restorans = $modelRestoran->getAllRestorans();
                $riwayatFile = __DIR__ . '/json/riwayat.json';
                $riwayatData = json_decode(file_get_contents($riwayatFile), true);
                include 'views/customer/rekomendasi.php';
        }
        break;

    case 'keranjang':
        include 'views/customer/keranjang.php';
        break;

    case 'riwayat':
        include 'views/customer/riwayat.php';
        break;

    case 'checkout':
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['totalPrice'], $data['diskon'], $data['keranjangItems'])) {
            $totalPrice = $data['totalPrice'];
            $diskon = $data['diskon'];
            $keranjangItems = $data['keranjangItems'];

            $loggedInUserId = $_SESSION['user_id'];
            $saldo = $objectPengguna->getSaldoLoggedInUser();

            if ($saldo >= $totalPrice) {
                $saldoBaru = $saldo - $totalPrice;
                $objectPengguna->updateSaldoMin($loggedInUserId, $saldoBaru);

                $objectPengguna->addRiwayat($loggedInUserId, $keranjangItems, $totalPrice);

                unset($_SESSION['keranjangItems']);
                unset($_SESSION['totalPrice']);
                unset($_SESSION['totalItems']);

                http_response_code(200);
                echo json_encode(['status' => 'success']);
                exit;
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Saldo Anda tidak mencukupi untuk checkout.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Data checkout tidak valid.']);
        }

        break;

    case 'simpan_keranjang':
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['keranjangItems']) && isset($data['totalPrice'])) {
            $_SESSION['totalPrice'] = $data['totalPrice'];
            $_SESSION['keranjangItems'] = $data['keranjangItems'];
            $_SESSION['totalItems'] = $data['totalItems'];

            http_response_code(200);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Data checkout tidak valid.']);
        }
        break;

    default:
        $restorans = $modelRestoran->getAllRestorans();
        $penggunas = $modelPengguna->getAllUsers();
        $transaksis = $modelTransaksi->getAllTransaksi();
        $vouchers = $modelVoucher->getAllVouchers();
        $diskons = $modelDiskon->getAllDiskons();
        $menus = $modelMenu->getAllMenus();

        include 'views/admin/admin_dashboard.php';
        break;
}
