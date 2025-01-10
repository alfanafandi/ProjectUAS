<?php
require_once __DIR__ . '/../domain_object/node_transaksi.php';
require_once 'model/pengguna_model.php';

class TransaksiModel
{
    private $penggunaModel;
    private $db;

    public function __construct(PenggunaModel $penggunaModel)
    {
        $this->penggunaModel = $penggunaModel;
        $this->db = new mysqli('localhost', 'root', '', 'projectdb');

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function addTransaksi($nama_pengguna, $jumlah_topup)
    {
        $stmt = $this->db->prepare("INSERT INTO transaksi (nama_pengguna, jumlah_topup, status) VALUES (?, ?, 'Pending')");
        $stmt->bind_param("si", $nama_pengguna, $jumlah_topup);
        $stmt->execute();
        $stmt->close();
    }

    public function getAllTransaksi()
    {
        $result = $this->db->query("SELECT * FROM transaksi");
        $transaksis = [];

        while ($row = $result->fetch_assoc()) {
            $transaksis[] = new Transaksi($row['transaksi_id'], $row['nama_pengguna'], $row['jumlah_topup'], $row['status']);
        }

        return $transaksis;
    }

    public function getTransaksiById($transaksi_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM transaksi WHERE transaksi_id = ?");
        $stmt->bind_param("i", $transaksi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $transaksi_data = $result->fetch_assoc();
        $stmt->close();

        if ($transaksi_data) {
            return new Transaksi(
                $transaksi_data['transaksi_id'],
                $transaksi_data['nama_pengguna'],
                $transaksi_data['jumlah_topup'],
                $transaksi_data['status']
            );
        }

        return null;
    }

    public function approveTransaksi($transaksi_id)
    {
        $stmt = $this->db->prepare("UPDATE transaksi SET status = 'Approved' WHERE transaksi_id = ? AND status = 'Pending'");
        $stmt->bind_param("i", $transaksi_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $transaksi = $this->getTransaksiById($transaksi_id);
            $user = $this->penggunaModel->getUserByUsername($transaksi->nama_pengguna);

            if ($user) {
                $this->penggunaModel->updateSaldo($user->user_id, $transaksi->jumlah_topup);
            }
        }

        $stmt->close();
    }

    public function rejectTransaksi($transaksi_id)
    {
        $stmt = $this->db->prepare("UPDATE transaksi SET status = 'Rejected' WHERE transaksi_id = ? AND status = 'Pending'");
        $stmt->bind_param("i", $transaksi_id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteTransaksi($transaksi_id)
    {
        $stmt = $this->db->prepare("DELETE FROM transaksi WHERE transaksi_id = ?");
        $stmt->bind_param("i", $transaksi_id);
        $stmt->execute();
        $stmt->close();
    }

    public function getSaldo($user_username)
    {
        $user = $this->penggunaModel->getUserByUsername($user_username);
        if ($user) {
            return $user->saldo;
        }
        return 0;
    }
}
