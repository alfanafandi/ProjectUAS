<?php
require_once __DIR__ . '/../domain_object/node_menu.php';
require_once __DIR__ . '/../domain_object/node_restoran.php';

class MenuModel
{
    private $menus = [];
    private $next_id = 1;
    private $data_file = __DIR__ . '/../json/menus.json';
    private $restoranModel;

    public function __construct(RestoranModel $restoranModel)
    {
        $this->restoranModel = $restoranModel;
        if (file_exists($this->data_file)) {
            $this->menus = $this->loadFromJson();
            $this->next_id = count($this->menus) + 1;
        } else {
            $this->initializeDefaultMenu();
        }
    }

    public function initializeDefaultMenu()
    {
        $this->addMenu(1, "Nasi Goreng", "Makanan", 20000);
        $this->addMenu(1, "Ayam Bakar", "Makanan", 30000);
        $this->addMenu(2, "Coca-cola", "Makanan", 30000);
        $this->addMenu(3, "Burger", "Makanan", 30000);

        $this->saveToJson();
    }

    public function addMenu($restoran_id, $menu_nama, $menu_kategori, $menu_harga)
    {
        $restoran = $this->restoranModel->getRestoranById($restoran_id);
        if ($restoran) {
            $menu = new \Makanan($this->next_id++, $restoran, $menu_nama, $menu_kategori, $menu_harga);
            $this->menus[] = $menu;
            $this->saveToJson();
        } else {
            throw new Exception('Restoran tidak ditemukan.');
        }
    }

    private function saveToJson()
    {
        $data = [
            'menus' => $this->menus,
            'next_id' => $this->next_id
        ];
        file_put_contents($this->data_file, json_encode($data, JSON_PRETTY_PRINT));
    }

    private function loadFromJson()
    {
        $data = json_decode(file_get_contents($this->data_file), true);
        $menus = array_map(function ($menu) {
            return new \Makanan(
                $menu['menu_id'],
                $this->restoranModel->getRestoranById($menu['menu_restoran']['restoran_id']),
                $menu['menu_nama'],
                $menu['menu_kategori'],
                $menu['menu_harga']
            );
        }, $data['menus']);
        $this->next_id = $data['next_id'];
        return $menus;
    }

    public function getAllMenus()
    {
        return $this->menus;
    }

    public function getMenuById($menu_id)
    {
        foreach ($this->menus as $menu) {
            if ($menu->menu_id == $menu_id) {
                return $menu;
            }
        }
        return null;
    }

    public function getMenusByRestoran($restoran_id)
    {
        $menus_by_restoran = [];
        foreach ($this->menus as $menu) {
            if ($menu->menu_restoran->restoran_id == $restoran_id) {
                $menus_by_restoran[] = $menu;
            }
        }
        return $menus_by_restoran;
    }

    public function updateMenu($menu_id, $menu_restoran, $menu_nama, $menu_kategori, $menu_harga)
    {
        foreach ($this->menus as $menu) {
            if ($menu->menu_id == $menu_id) {
                $restoran = $this->restoranModel->getRestoranById($menu_restoran);
                $menu->menu_restoran = $restoran;
                $menu->menu_nama = $menu_nama;
                $menu->menu_kategori = $menu_kategori;
                $menu->menu_harga = $menu_harga;
                $this->saveToJson();
                return true;
            }
        }
        return false;
    }

    public function deleteMenu($menu_id)
    {
        foreach ($this->menus as $key => $menu) {
            if ($menu->menu_id == $menu_id) {
                unset($this->menus[$key]);
                $this->menus = array_values($this->menus);
                $this->saveToJson();
                return true;
            }
        }
        return false;
    }

    public function getMenuByName($menu_nama)
    {
        foreach ($this->menus as $menu) {
            if ($menu_nama == $menu->menu_nama) {
                return $menu;
            }
        }
        return null;
    }
}
