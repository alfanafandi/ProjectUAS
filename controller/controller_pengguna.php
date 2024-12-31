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

    public function addPengguna($user_username, $user_password, $saldo)
    {
        $this->penggunaModel->addUser($user_username, $user_password, $saldo);
        header('Location: index.php?modul=pengguna');
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
        if (!$result) {
            throw new Exception('Pengguna tidak ditemukan.');
        } else {
            header('Location: /index.php?modul=pengguna');
        }
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
    }

    function addRiwayat($user_id, $keranjangItems, $totalPrice)
    {
        $riwayatFilePath = __DIR__ . '/../json/riwayat.json';

        $riwayatData = [];
        if (file_exists($riwayatFilePath)) {
            $riwayatData = json_decode(file_get_contents($riwayatFilePath), true);
        }

        $isDuplicate = false;
        foreach ($riwayatData as $entry) {
            if ($entry['user_id'] === $user_id && $entry['items'] === $keranjangItems && $entry['totalPrice'] === $totalPrice) {
                $isDuplicate = true;
                break;
            }
        }

        if (!$isDuplicate) {
            $riwayatData[] = [
                'user_id' => $user_id,
                'items' => $keranjangItems,
                'totalPrice' => $totalPrice,
                'timestamp' => date('Y-m-d')
            ];

            file_put_contents($riwayatFilePath, json_encode($riwayatData, JSON_PRETTY_PRINT));
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
