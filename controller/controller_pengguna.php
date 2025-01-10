<?php

require_once 'model/pengguna_model.php';

class controllerPengguna
{
    private $penggunaModel;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
    }

    public function listPengguna()
    {
        $users = $this->penggunaModel->getAllUsers();
        include 'views/admin/pengguna_list.php';
    }

    public function addPengguna($user_username, $user_password, $saldo = 0)
    {
        $this->penggunaModel->addUser($user_username, $user_password, $saldo);
        header('Location: index.php?modul=pengguna');
    }

    public function registerPengguna($user_username, $user_password)
    {
        $this->penggunaModel->registerUser($user_username, $user_password, 0); // Ensure saldo is set to 0
        header('Location: index.php?modul=login');
    }

    public function editById($user_id)
    {
        $user = $this->penggunaModel->getUserById($user_id);
        include 'views/admin/pengguna_edit.php';
    }

    public function updatePengguna($user_id, $user_username, $user_password, $saldo)
    {
        $this->penggunaModel->updateUser($user_id, $user_username, $user_password, $saldo);
        header('Location: index.php?modul=pengguna');
    }

    public function deletePengguna($user_id)
    {
        $result = $this->penggunaModel->deleteUser($user_id);
        header('Location: index.php?modul=pengguna');
    }


    public function updateSaldo($user_id, $amount)
    {
        $result = $this->penggunaModel->updateSaldo($user_id, $amount);
        if (!$result) {
            throw new Exception("Saldo tidak dapat diperbarui. Pengguna dengan ID {$user_id} tidak ditemukan.");
        }
        header('Location: index.php?modul=pengguna');
    }

    public function updateSaldoMin($user_id, $amount)
    {
        $result = $this->penggunaModel->updateSaldoMin($user_id, $amount);
        if (!$result) {
            throw new Exception("Saldo tidak dapat diperbarui. Pengguna dengan ID {$user_id} tidak ditemukan.");
        }
    }

    function addRiwayat($user_id, $keranjangItems, $totalPrice)
    {
        // Koneksi ke database
        $host = 'localhost'; // Ganti sesuai konfigurasi
        $db = 'ProjectDB'; // Ganti dengan nama database Anda
        $user = 'root'; // Ganti jika username database berbeda
        $password = ''; // Ganti jika ada password
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Masukkan data ke tabel riwayat
            $query = "INSERT INTO riwayat (user_id, items, total_price) VALUES (:user_id, :items, :total_price)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':user_id' => $user_id,
                ':items' => json_encode($keranjangItems), // Simpan items dalam format JSON
                ':total_price' => $totalPrice
            ]);

            return true; // Berhasil
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }


    public function getSaldo($user_id)
    {
        $saldo = $this->penggunaModel->getSaldo($user_id);
        if ($saldo === null) {
            throw new Exception("Saldo tidak ditemukan untuk pengguna dengan ID {$user_id}.");
        }
        return $saldo;
    }

    public function getUserById($user_id)
    {
        return $this->penggunaModel->getUserById($user_id);
    }

    public function getSaldoLoggedInUser()
    {
        if (!isset($_SESSION['user_id'])) {
            throw new Exception('Tidak ada pengguna yang sedang login.');
        }

        $user_id = $_SESSION['user_id'];
        return $this->penggunaModel->getSaldo($user_id);
    }
}
