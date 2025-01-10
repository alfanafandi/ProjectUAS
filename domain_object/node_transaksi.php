<?php
class Transaksi
{
    public $transaksi_id;
    public $nama_pengguna;
    public $jumlah_topup;
    public $status;

    public function __construct($transaksi_id, $nama_pengguna, $jumlah_topup, $status)
    {
        $this->transaksi_id = $transaksi_id;
        $this->nama_pengguna = $nama_pengguna;
        $this->jumlah_topup = $jumlah_topup;
        $this->status = $status;
    }
}
