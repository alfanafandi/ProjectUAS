<?php
require_once __DIR__ . '/../domain_object/node_voucher.php';

class VoucherModel
{
    private $db;

    public function __construct()
    {
        $this->db = new mysqli('localhost', 'root', '', 'ProjectDB');
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function addVoucher($kode, $diskon)
    {
        $stmt = $this->db->prepare("INSERT INTO vouchers (kode, diskon) VALUES (?, ?)");
        $stmt->bind_param("si", $kode, $diskon);
        $stmt->execute();
        $stmt->close();
    }

    public function getAllVouchers()
    {
        $result = $this->db->query("SELECT * FROM vouchers");
        $vouchers = [];
        while ($row = $result->fetch_assoc()) {
            $vouchers[] = new \Voucher($row['voucher_id'], $row['kode'], $row['diskon']);
        }
        return $vouchers;
    }

    public function getVoucherById($voucher_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM vouchers WHERE voucher_id = ?");
        $stmt->bind_param("i", $voucher_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $voucher = $result->fetch_assoc();
        $stmt->close();
        return $voucher ? new \Voucher($voucher['voucher_id'], $voucher['kode'], $voucher['diskon']) : null;
    }

    public function updateVoucher($voucher_id, $voucher_kode, $voucher_diskon)
    {
        $stmt = $this->db->prepare("UPDATE vouchers SET kode = ?, diskon = ? WHERE voucher_id = ?");
        $stmt->bind_param("sii", $voucher_kode, $voucher_diskon, $voucher_id);
        $stmt->execute();
        $stmt->close();
    }

    public function getVoucherByCode($kode)
    {
        $stmt = $this->db->prepare("SELECT * FROM vouchers WHERE kode = ?");
        $stmt->bind_param("s", $kode);
        $stmt->execute();
        $result = $stmt->get_result();
        $voucher = $result->fetch_assoc();
        $stmt->close();
        return $voucher ? new \Voucher($voucher['voucher_id'], $voucher['kode'], $voucher['diskon']) : null;
    }

    public function deleteVoucher($voucher_id)
    {
        $stmt = $this->db->prepare("DELETE FROM vouchers WHERE voucher_id = ?");
        $stmt->bind_param("i", $voucher_id);
        $stmt->execute();
        $stmt->close();
    }
}
