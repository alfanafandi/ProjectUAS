<?php
require_once __DIR__ . '/../domain_object/node_pengguna.php';

class PenggunaModel
{
    private $db;

    public function __construct()
    {
        $this->db = new mysqli('localhost', 'root', '', 'ProjectDB');
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function addUser($user_username, $user_password, $saldo = 0)
    {
        $stmt = $this->db->prepare("INSERT INTO users (user_username, user_password, saldo) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $user_username, $user_password, $saldo);
        $stmt->execute();
        $stmt->close();
    }

    public function registerUser($user_username, $user_password, $saldo = 0)
    {
        return $this->addUser($user_username, $user_password, $saldo);
    }

    public function getAllUsers()
    {
        $result = $this->db->query("SELECT * FROM users");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = new \User($row['user_id'], $row['user_username'], $row['user_password'], $row['saldo']);
        }
        return $users;
    }

    public function getUserById($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ? new \User($user['user_id'], $user['user_username'], $user['user_password'], $user['saldo']) : null;
    }

    public function getUserByUsername($user_username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_username = ?");
        $stmt->bind_param("s", $user_username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ? new \User($user['user_id'], $user['user_username'], $user['user_password'], $user['saldo']) : null;
    }

    public function updateUser($user_id, $user_username, $user_password, $saldo)
    {
        $stmt = $this->db->prepare("UPDATE users SET user_username = ?, user_password = ?, saldo = ? WHERE user_id = ?");
        $stmt->bind_param("ssii", $user_username, $user_password, $saldo, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteUser($user_id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    public function updateSaldo($user_id, $amount)
    {
        $stmt = $this->db->prepare("UPDATE users SET saldo = saldo + ? WHERE user_id = ?");
        $stmt->bind_param("ii", $amount, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    public function updateSaldoMin($user_id, $saldoBaru)
    {
        if ($saldoBaru < 0) {
            error_log("Error: saldoBaru negatif untuk user_id " . $user_id);
            return false;
        }

        $stmt = $this->db->prepare("UPDATE users SET saldo = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $saldoBaru, $user_id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function getSaldo($user_id)
    {
        $stmt = $this->db->prepare("SELECT saldo FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $saldo = $result->fetch_assoc()['saldo'];
        $stmt->close();
        return $saldo;
    }

    public function getSaldoByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT saldo FROM users WHERE user_username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($saldo);
        $stmt->fetch();
        $stmt->close();

        return $saldo;
    }
}
