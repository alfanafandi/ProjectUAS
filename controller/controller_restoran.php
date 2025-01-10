<?php

require_once 'model/restoran_model.php';

class ControllerRestoran
{
    private $restoranModel;

    public function __construct()
    {
        $this->restoranModel = new RestoranModel();
    }

    public function listRestorans()
    {
        $restorans = $this->restoranModel->getAllRestorans();
        include 'views/admin/restoran_list.php';
    }

    public function addRestoran($restoran_nama, $restoran_password, $restoran_gambar)
    {
        $this->restoranModel->addRestoran($restoran_nama, $restoran_password, $restoran_gambar);
        header('Location: index.php?modul=restoran');
        exit;
    }

    public function getRestoranById($restoran_id)
    {
        return $this->restoranModel->getRestoranById($restoran_id);
    }

    public function editById($restoran_id)
    {
        $restoran = $this->restoranModel->getRestoranById($restoran_id);
        if ($restoran) {
            include 'views/admin/restoran_edit.php';
        } else {
            throw new Exception('Restoran tidak ditemukan.');
        }
    }

    public function belanjaById($restoran_id)
    {
        $restoran = $this->restoranModel->getRestoranById($restoran_id);
        $menus_by_restoran = [];
        if ($restoran) {
            $menus_by_restoran = (new controllerMenu($this->restoranModel))->getMenusByRestoran($restoran_id);
            include 'views/customer/resto.php';
        } else {
            die('Restoran tidak ditemukan.');
        }
    }

    public function updateRestoran($id, $restoran_nama, $restoran_password, $restoran_gambar)
    {
        $result = $this->restoranModel->updateRestoran($id, $restoran_nama, $restoran_password, $restoran_gambar);
        if ($result) {
            header('Location: index.php?modul=restoran');
            exit;
        } else {
            throw new Exception('Restoran tidak ditemukan atau gagal diupdate.');
        }
    }

    public function deleteRestoran($id)
    {
        $result = $this->restoranModel->deleteRestoran($id);
        if ($result) {
            header('Location: index.php?modul=restoran');
            exit;
        } else {
            throw new Exception('Restoran tidak ditemukan.');
        }
    }

    public function getListRestoranName()
    {
        $listRestoranName = [];
        foreach ($this->restoranModel->getAllRestorans() as $restoran) {
            $listRestoranName[] = $restoran->restoran_nama;
        }
        return $listRestoranName;
    }

    public function getRestoranByName($name)
    {
        return $this->restoranModel->getRestoranByName($name);
    }
}
