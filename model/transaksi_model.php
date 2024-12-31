<?php
require_once __DIR__ . '/../domain_object/node_transaksi.php';
require_once 'model/pengguna_model.php';

class TransaksiModel
{
    private $transaksi = [];
    private $next_id = 1;
    private $penggunaModel;

    private $filePath = __DIR__ . '/../json/transaksi.json';

    public function __construct(PenggunaModel $penggunaModel)
    {
        $this->penggunaModel = $penggunaModel;

        // Memuat data dari file JSON jika ada
        $this->loadFromFile();
        $this->next_id = count($this->transaksi) + 1;
    }

    // Menyimpan data transaksi ke file JSON
    private function saveToFile()
    {
        $data = [];
        foreach ($this->transaksi as $transaksi) {
            $data[] = [
                'transaksi_id' => $transaksi->transaksi_id,
                'nama_pengguna' => $transaksi->nama_pengguna,
                'jumlah_topup' => $transaksi->jumlah_topup,
                'status' => $transaksi->status,
            ];
        }
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    // Memuat data transaksi dari file JSON
    private function loadFromFile()
    {
        if (file_exists($this->filePath)) {
            $data = json_decode(file_get_contents($this->filePath), true);
            foreach ($data as $transaksi_data) {
                $this->transaksi[] = new Transaksi(
                    $transaksi_data['transaksi_id'],
                    $transaksi_data['nama_pengguna'],
                    $transaksi_data['jumlah_topup'],
                    $transaksi_data['status']
                );
            }
        }
    }

    public function addTransaksi($nama_pengguna, $jumlah_topup)
    {
        $transaksi = new Transaksi($this->next_id++, $nama_pengguna, $jumlah_topup, "Pending");
        $this->transaksi[] = $transaksi;
        $this->saveToFile();
    }

    public function getAllTransaksi()
    {
        return $this->transaksi;
    }

    public function getTransaksiById($transaksi_id)
    {
        foreach ($this->transaksi as $transaksi) {
            if ($transaksi->transaksi_id == $transaksi_id) {
                return $transaksi;
            }
        }
        return null;
    }

    public function approveTransaksi($transaksi_id)
    {
        foreach ($this->transaksi as $transaksi) {
            if ($transaksi->transaksi_id == $transaksi_id && $transaksi->status == "Pending") {
                $transaksi->status = "Approved";
                $user = $this->penggunaModel->getUserByUsername($transaksi->nama_pengguna['user_username']);

                if ($user) {
                    $this->penggunaModel->updateSaldo($user->user_id, $transaksi->jumlah_topup);
                    $this->penggunaModel->saveToFile();
                }

                $this->saveToFile();
                return true;
            }
        }
        return false;
    }


    public function rejectTransaksi($transaksi_id)
    {
        foreach ($this->transaksi as $transaksi) {
            if ($transaksi->transaksi_id == $transaksi_id && $transaksi->status == "Pending") {
                $transaksi->status = "Rejected";
                $this->saveToFile();
                return true;
            }
        }
        return false;
    }

    public function deleteTransaksi($transaksi_id)
    {
        foreach ($this->transaksi as $key => $transaksi) {
            if ($transaksi->transaksi_id == $transaksi_id) {
                unset($this->transaksi[$key]);
                $this->transaksi = array_values($this->transaksi);
                $this->saveToFile();
                return true;
            }
        }
        return false;
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
