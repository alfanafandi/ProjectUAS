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

        if (isset($_SESSION['user_id'])) {
            $_SESSION['saldo'] = $this->transaksiModel->getSaldo($_SESSION['user_id']);
        }

        header('Location: index.php?modul=transaksi');
    }


    public function deleteTransaksi($transaksi_id)
    {
        $result = $this->transaksiModel->deleteTransaksi($transaksi_id);
        if (!$result) {
            throw new Exception('Transaksi tidak ditemukan.');
        } else {
            header('Location: index.php?modul=transaksi');
        }
    }

    public function rejectTransaksi($transaksi_id)
    {
        $result = $this->transaksiModel->rejectTransaksi($transaksi_id);
        if ($result) {
            header('Location: index.php?modul=transaksi&status=rejected');
        } else {
            header('Location: index.php?modul=transaksi&error=reject_failed');
        }
    }
}
