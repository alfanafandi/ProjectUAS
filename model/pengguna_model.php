<?php
require_once __DIR__ . '/../domain_object/node_pengguna.php';

class PenggunaModel
{
    private $users = [];
    private $next_id = 1;

    private $filePath = __DIR__ . '/../json/users.json';

    public function __construct()
    {
        $this->loadFromFile();
        $this->next_id = count($this->users) + 1;
    }

    public function saveToFile()
    {
        $data = [];
        foreach ($this->users as $user) {
            $data[] = [
                'user_id' => $user->user_id,
                'user_username' => $user->user_username,
                'user_password' => $user->user_password,
                'saldo' => $user->saldo
            ];
        }
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function loadFromFile()
    {
        if (file_exists($this->filePath)) {
            $data = json_decode(file_get_contents($this->filePath), true);

            foreach ($data as $user_data) {
                $this->users[] = new \User($user_data['user_id'], $user_data['user_username'], $user_data['user_password'], $user_data['saldo']);
            }
        } else {
            $this->initializeDefaultUsers();
        }
    }

    public function addUser($user_username, $user_password, $saldo)
    {
        $existing_user = $this->getUserByUsername($user_username);
        if ($existing_user) {
            return false;
        }
        $user = new \User($this->next_id++, $user_username, $user_password, $saldo);
        $this->users[] = $user;
        $this->saveToFile();

        return true;
    }

    public function initializeDefaultUsers()
    {
        $this->addUser("admin", "admin123", 0);
        $this->addUser("restoran", "restoran123", 0);
        $this->addUser("customer", "customer123", 500000);
        $this->saveToFile();
    }

    private function saveToSession()
    {
        $_SESSION['users'] = serialize($this->users);
    }

    public function getAllUsers()
    {
        return $this->users;
    }

    public function getUserById($user_id)
    {
        foreach ($this->users as $user) {
            if ($user->user_id == $user_id) {
                return $user;
            }
        }
        return null;
    }

    public function getUserByUsername($user_username)
    {
        foreach ($this->users as $user) {
            if ($user->user_username === $user_username) {
                return $user;
            }
        }
        return null;
    }

    public function updateUser($user_id, $user_username, $user_password, $saldo)
    {
        foreach ($this->users as $user) {
            if ($user->user_id == $user_id) {
                $user->user_username = $user_username;
                $user->user_password = $user_password;
                $user->saldo = $saldo;
                $this->saveToFile();
                return true;
            }
        }
        return false;
    }

    public function deleteUser($user_id)
    {
        foreach ($this->users as $key => $user) {
            if ($user->user_id == $user_id) {
                unset($this->users[$key]);
                $this->users = array_values($this->users);
                $this->saveToFile();
                return true;
            }
        }
        return false;
    }

    // public function authenticateUser($user_username, $user_password)
    // {
    //     $user = $this->getUserByUsername($user_username);
    //     $user_password = trim($user_password); // Pastikan tidak ada spasi tambahan
    //     if ($user && $user_password === $user->user_password) { // Bandingkan langsung
    //         return $user;
    //     }
    //     return null;
    // }

    public function updateSaldo($user_id, $amount)
    {
        foreach ($this->users as $user) {
            if ($user->user_id == $user_id) {
                $user->saldo += $amount;
                $this->saveToFile();
                return true;
            }
        }
        return false;
    }

    public function updateSaldoMin($user_id, $saldoBaru)
    {
        // Validasi saldo baru
        if ($saldoBaru < 0) {
            error_log("Error: saldoBaru negatif untuk user_id " . $user_id);
            return false;
        }

        // Log data pengguna sebelum update
        error_log("Data users sebelum update: " . json_encode($this->users));

        foreach ($this->users as &$user) {
            if ($user->user_id == $user_id) {
                // Log saldo sebelum perubahan
                error_log("Saldo lama untuk user_id " . $user_id . ": " . $user->saldo);

                // Perbarui saldo
                $user->saldo = $saldoBaru;

                // Simpan perubahan ke file
                $this->saveToFile();

                // Log saldo setelah perubahan
                error_log("Saldo baru untuk user_id " . $user_id . ": " . $saldoBaru);

                return true;
            }
        }

        // Jika user_id tidak ditemukan
        error_log("Error: user_id " . $user_id . " tidak ditemukan.");
        return false;
    }




    public function getSaldoByUserId($user_id)
    {
        foreach ($this->users as $user) {
            if ($user->user_id == $user_id) {
                return $user->saldo;
            }
        }
        return null;
    }

    public function getSaldo($user_id)
    {
        foreach ($this->users as $user) {
            if ($user->user_id == $user_id) {
                return $user->saldo;
            }
        }
        return null;
    }
}
