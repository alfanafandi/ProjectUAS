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

    public function addMenu($menu_restoran, $menu_nama, $menu_kategori, $menu_harga, $menu_gambar)
    {
        $this->menuModel->addMenu($menu_restoran, $menu_nama, $menu_kategori, $menu_harga, $menu_gambar);
        header('Location: index.php?modul=menu');
    }

    public function editById($menu_id)
    {
        $menu = $this->menuModel->getMenuById($menu_id);
        include 'views/restoran/menu_edit.php';
    }

    public function updateMenu($menu_id, $menu_restoran, $menu_nama, $menu_kategori, $menu_harga, $menu_gambar)
    {
        if (empty($menu_gambar['name'])) {
            $menu = $this->menuModel->getMenuById($menu_id);
            $menu_gambar = $menu['menu_gambar'];
        } else {
            $uploadDir = 'uploads/menus/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadFile = $uploadDir . basename($menu_gambar['name']);
            move_uploaded_file($menu_gambar['tmp_name'], $uploadFile);
            $menu_gambar = $uploadFile;
        }
        $this->menuModel->updateMenu($menu_id, $menu_restoran, $menu_nama, $menu_kategori, $menu_harga, $menu_gambar);
        header('Location: index.php?modul=menu');
        exit;
    }

    public function deleteMenu($menu_id)
    {
        $this->menuModel->deleteMenu($menu_id);
        header('Location: index.php?modul=menu');
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
