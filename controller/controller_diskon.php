<?php

require_once 'model/diskon_model.php';
require_once 'model/restoran_model.php';

class controllerDiskon
{
    private $diskonModel;
    private $restoranModel;

    public function __construct(RestoranModel $restoranModel)
    {
        $this->restoranModel = $restoranModel;
        $this->diskonModel = new DiskonModel($this->restoranModel);
    }

    public function listDiskonsByRestoran($restoran_id)
    {
        $diskons = $this->diskonModel->getDiskonsByRestoran($restoran_id);
        include 'views/restoran/diskon_list.php';
    }

    public function addDiskon($restoran_id, $diskon_nama, $diskon_presentase)
    {
        $this->diskonModel->addDiskon($restoran_id, $diskon_nama, $diskon_presentase);
        header("Location: index.php?modul=diskon");
    }

    public function editById($diskon_id)
    {
        $diskon = $this->diskonModel->getDiskonById($diskon_id);
        if ($diskon) {
            include 'views/restoran/diskon_edit.php';
        } else {
            header('Location: index.php?modul=diskon&error=not_found');
        }
    }

    public function deleteDiskon($diskon_id)
    {
        $result = $this->diskonModel->deleteDiskon($diskon_id);
        if ($result) {
            header("Location: index.php?modul=diskon");
        }
    }

    public function updateDiskon($diskon_id, $restoran_id, $diskon_nama, $diskon_presentase)
    {
        $result = $this->diskonModel->updateDiskon($diskon_id, $restoran_id, $diskon_nama, $diskon_presentase);
        if ($result) {
            header("Location: index.php?modul=diskon");
        }
    }
}
