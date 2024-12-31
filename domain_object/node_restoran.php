<?php
class Restoran
{
    public $restoran_id;
    public $restoran_nama;
    public $restoran_password;
    public $restoran_gambar;

    public function __construct($restoran_id, $restoran_nama, $restoran_password, $restoran_gambar)
    {
        $this->restoran_id = $restoran_id;
        $this->restoran_nama = $restoran_nama;
        $this->restoran_password = $restoran_password;
        $this->restoran_gambar = $restoran_gambar;
    }
}
