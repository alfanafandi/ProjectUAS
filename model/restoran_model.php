<?php
require_once __DIR__ . '/../domain_object/node_restoran.php';

class RestoranModel
{
    private $restorans = [];
    private $next_id = 1;
    private $data_file = __DIR__ . '/../json/restorans.json';

    public function __construct()
    {
        if (file_exists($this->data_file)) {
            $this->loadFromJson();
        } else {
            $this->initializeDefaultRestoran();
        }
    }

    public function initializeDefaultRestoran()
    {
        $defaultImageDir = 'uploads/restorans/';

        if (!is_dir($defaultImageDir)) {
            mkdir($defaultImageDir, 0755, true);
        }

        $this->addRestoran("Aiola", "123", $defaultImageDir . "aiola.jpg");
        $this->addRestoran("Ayam Bakar Pak D", "123", $defaultImageDir . "ayam_bakar_pak_d.jpg");
        $this->addRestoran("MC Donald", "123", $defaultImageDir . "mc_donald.jpg");

        $this->saveToJson();
    }


    public function addRestoran($restoran_nama, $restoran_password, $restoran_gambar)
    {
        $restoran = new Restoran($this->next_id++, $restoran_nama, $restoran_password, $restoran_gambar);
        $this->restorans[] = $restoran;
        $this->saveToJson();
    }

    private function saveToJson()
    {
        $data = [
            'restorans' => $this->restorans,
            'next_id' => $this->next_id
        ];
        file_put_contents($this->data_file, json_encode($data, JSON_PRETTY_PRINT));
    }

    private function loadFromJson()
    {
        $data = json_decode(file_get_contents($this->data_file), true);
        $this->restorans = array_map(function ($restoran) {
            return new Restoran(
                $restoran['restoran_id'],
                $restoran['restoran_nama'],
                $restoran['restoran_password'],
                $restoran['restoran_gambar']
            );
        }, $data['restorans']);
        $this->next_id = $data['next_id'];
    }

    public function getAllRestorans()
    {
        return $this->restorans;
    }

    public function getRestoranById($restoran_id)
    {
        foreach ($this->restorans as $restoran) {
            if ($restoran->restoran_id == $restoran_id) {
                return $restoran;
            }
        }
        return null;
    }

    public function updateRestoran($restoran_id, $restoran_nama, $restoran_password, $restoran_gambar)
    {
        foreach ($this->restorans as $restoran) {
            if ($restoran->restoran_id == $restoran_id) {
                $restoran->restoran_nama = $restoran_nama;
                $restoran->restoran_password = $restoran_password;
                $restoran->restoran_gambar = $restoran_gambar;
                $this->saveToJson();
                return true;
            }
        }
        return false;
    }

    public function deleteRestoran($restoran_id)
    {
        foreach ($this->restorans as $key => $restoran) {
            if ($restoran->restoran_id == $restoran_id) {
                unset($this->restorans[$key]);
                $this->restorans = array_values($this->restorans);
                $this->saveToJson();
                return true;
            }
        }
        return false;
    }

    public function getRestoranByName($restoran_nama)
    {
        foreach ($this->restorans as $restoran) {
            if ($restoran->restoran_nama == $restoran_nama) {
                return $restoran;
            }
        }
        return null;
    }
}
