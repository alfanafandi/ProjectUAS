<?php

require_once 'model/voucher_model.php';

class controllerVoucher
{
    private $voucherModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
    }

    public function applyVoucher($voucher_kode)
    {
        $voucher = $this->voucherModel->getVoucherByCode($voucher_kode);
        if ($voucher) {
            // Simpan kode voucher dan diskon ke session
            $_SESSION['voucher'] = [
                'kode' => $voucher['kode'],
                'diskon' => $voucher['diskon']
            ];
            return true;
        }
        return false;
    }

    public function listVouchers()
    {
        $vouchers = $this->voucherModel->getAllVouchers();
        include 'views/admin/voucher_list.php';
    }

    public function listCustomerVouchers()
    {
        $vouchers = $this->voucherModel->getAllVouchers(); // Ambil data voucher
        include 'views/customer/keranjang.php'; // Tampilkan data ke view
    }

    public function getVoucherByCode($voucher_kode)
    {
        $voucher = $this->voucherModel->getVoucherByCode($voucher_kode);
    }

    public function addVoucher($kode, $diskon)
    {
        $this->voucherModel->addVoucher($kode, $diskon);
        header('Location: index.php?modul=voucher');
    }

    public function updateVoucher($id, $voucher_kode, $voucher_diskon)
    {
        $this->voucherModel->updateVoucher($id, $voucher_kode, $voucher_diskon);
        header('Location: index.php?modul=voucher');
    }

    public function deleteVoucher($voucher_id)
    {
        $result = $this->voucherModel->deleteVoucher($voucher_id);
        if (!$result) {
            throw new Exception('Voucher tidak ditemukan.');
        } else {
            header('Location: index.php?modul=voucher');
        }
    }

    public function editById($voucher_id)
    {
        $voucher = $this->voucherModel->getVoucherById($voucher_id);
        include 'views/admin/voucher_edit.php';
    }

    public function viewDetailVoucher($voucher_id)
    {
        $voucher = $this->voucherModel->getVoucherById($voucher_id);
        if ($voucher) {
            include 'views/voucher_detail.php';
        } else {
            header('Location: index.php?modul=voucher&error=not_found');
        }
    }
}
