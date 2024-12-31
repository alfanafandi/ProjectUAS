<?php
class Makanan
{
    public $menu_id;
    public $menu_restoran;
    public $menu_nama;
    public $menu_kategori;
    public $menu_harga;

    public $menu_gambar;

    function __construct($menu_id, $menu_restoran, $menu_nama, $menu_kategori, $menu_harga, $menu_gambar)
    {
        $this->menu_id = $menu_id;
        $this->menu_restoran = $menu_restoran;
        $this->menu_nama = $menu_nama;
        $this->menu_kategori = $menu_kategori;
        $this->menu_harga = $menu_harga;
        $this->menu_gambar = $menu_gambar;
    }
}
