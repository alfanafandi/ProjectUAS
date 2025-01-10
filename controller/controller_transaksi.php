<?php

require_once 'model/transaksi_model.php';

class ControllerTransaksi
{
    private $transaksiModel;
    private $penggunaModel;

    public function __construct(PenggunaModel $penggunaModel)
    {
        $this->penggunaModel = $penggunaModel;
        $this->transaksiModel = new TransaksiModel($penggunaModel);
    }

    public function listTransaksi()
    {
        $transaksis = $this->transaksiModel->getAllTransaksi();
        include 'views/admin/transaksi_list.php';
    }

    public function addTransaksi($nama_pengguna, $jumlah_topup)
    {
        $this->transaksiModel->addTransaksi($nama_pengguna, $jumlah_topup);
        header('Location: index.php?modul=customer_dashboard');
    }

    public function approveTransaksi($id)
    {
        $this->transaksiModel->approveTransaksi($id);

        if (isset($_SESSION['username'])) {
            $_SESSION['saldo'] = $this->penggunaModel->getSaldoByUsername($_SESSION['username']);
        }

        header('Location: index.php?modul=transaksi');
    }

    public function deleteTransaksi($transaksi_id)
    {
        $result = $this->transaksiModel->deleteTransaksi($transaksi_id);
        header('Location: index.php?modul=transaksi');
    }
}
