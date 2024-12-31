<?php

require_once 'model/menu_model.php';

class controllerMenu
{
    private $menuModel;

    private $restoranModel;

    public function __construct(RestoranModel $restoranModel)
    {
        $this->restoranModel = $restoranModel;
        $this->menuModel = new MenuModel($restoranModel);
    }

    public function listMenus()
    {
        $menus = $this->menuModel->getAllMenus();
        include 'views/restoran/menu_list.php';
    }

    public function addMenu($menu_restoran, $menu_nama, $menu_kategori, $menu_harga)
    {
        $this->menuModel->addMenu($menu_restoran, $menu_nama, $menu_kategori, $menu_harga);
        header('Location: index.php?modul=menu');
    }

    public function editById($menu_id)
    {
        $menu = $this->menuModel->getMenuById($menu_id);
        include 'views/restoran/menu_edit.php';
    }

    public function updateMenu($menu_id, $menu_restoran, $menu_nama, $menu_kategori, $menu_harga)
    {
        $this->menuModel->updateMenu($menu_id, $menu_restoran, $menu_nama, $menu_kategori, $menu_harga);
        header('Location: index.php?modul=menu');
    }

    public function deleteMenu($menu_id)
    {
        $result = $this->menuModel->deleteMenu($menu_id);
        if (!$result) {
            throw new Exception('Menu tidak ditemukan.');
        } else {
            header('Location: index.php?modul=menu');
        }
    }

    public function getMenusByRestoran($restoran_id)
    {
        return $this->menuModel->getMenusByRestoran($restoran_id);
    }

    public function getMenuByName($menu_nama)
    {
        return $this->menuModel->getMenuByName($menu_nama);
    }
}
