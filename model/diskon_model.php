<?php
require_once __DIR__ . '/../domain_object/node_diskon.php';
require_once __DIR__ . '/../domain_object/node_restoran.php';

class DiskonModel
{
    private $diskons = [];
    private $next_id = 1;
    private $data_file = __DIR__ . '/../json/diskons.json';
    private $restoranModel;

    public function __construct(RestoranModel $restoranModel)
    {
        $this->restoranModel = $restoranModel;
        if (file_exists($this->data_file)) {
            $this->diskons = $this->loadFromJson();
            $this->next_id = count($this->diskons) + 1;
        } else {
            $this->initializeDefaultDiskon();
        }
    }

    public function initializeDefaultDiskon()
    {
        $this->addDiskon(1, "Diskon Hari Raya", 10);
        $this->addDiskon(2, "Diskon Ulang Tahun", 20);
        $this->addDiskon(3, "Diskon Kemerdekaan", 15);
        $this->saveToJson();
    }

    public function addDiskon($restoran_id, $diskon_nama, $diskon_presentase)
    {
        $restoran = $this->restoranModel->getRestoranById($restoran_id);
        if ($restoran) {
            $diskon = new \Diskon($this->next_id++, $restoran, $diskon_nama, $diskon_presentase);
            $this->diskons[] = $diskon;
            $this->saveToJson();
        } else {
            throw new Exception('Restoran tidak ditemukan.');
        }
    }

    private function saveToJson()
    {
        $data = [
            'diskons' => array_map(function ($diskon) {
                return [
                    'diskon_id' => $diskon->diskon_id,
                    'diskon_restoran' => [
                        'restoran_id' => $diskon->diskon_restoran->restoran_id,
                        'restoran_nama' => $diskon->diskon_restoran->restoran_nama
                    ],
                    'diskon_nama' => $diskon->diskon_nama,
                    'diskon_presentase' => $diskon->diskon_presentase
                ];
            }, $this->diskons),
            'next_id' => $this->next_id
        ];
        file_put_contents($this->data_file, json_encode($data, JSON_PRETTY_PRINT));
    }

    private function loadFromJson()
    {
        $data = json_decode(file_get_contents($this->data_file), true);
        $diskons = array_map(function ($item) {
            return new \Diskon(
                $item['diskon_id'],
                $this->restoranModel->getRestoranById($item['diskon_restoran']['restoran_id']),
                $item['diskon_nama'],
                $item['diskon_presentase']
            );
        }, $data['diskons']);
        $this->next_id = $data['next_id'];
        return $diskons;
    }

    public function getAllDiskons()
    {
        return $this->diskons;
    }

    public function getDiskonsByRestoran($restoran_id)
    {
        $diskons_by_restoran = [];
        foreach ($this->diskons as $diskon) {
            if ($diskon->diskon_restoran->restoran_id == $restoran_id) {
                $diskons_by_restoran[] = $diskon;
            }
        }
        return $diskons_by_restoran;
    }

    public function getDiskonById($diskon_id)
    {
        foreach ($this->diskons as $diskon) {
            if ($diskon->diskon_id == $diskon_id) {
                return $diskon;
            }
        }
        return null;
    }

    public function updateDiskon($diskon_id, $restoran_id, $diskon_nama, $diskon_presentase)
    {
        foreach ($this->diskons as $diskon) {
            if ($diskon->diskon_id == $diskon_id) {
                $restoran = $this->restoranModel->getRestoranById($restoran_id);
                if ($restoran) {
                    $diskon->diskon_restoran = $restoran;
                    $diskon->diskon_nama = $diskon_nama;
                    $diskon->diskon_presentase = $diskon_presentase;
                    $this->saveToJson();
                    return true;
                } else {
                    throw new Exception('Restoran tidak ditemukan.');
                }
            }
        }
        return false;
    }

    public function deleteDiskon($diskon_id)
    {
        foreach ($this->diskons as $key => $diskon) {
            if ($diskon->diskon_id == $diskon_id) {
                unset($this->diskons[$key]);
                $this->diskons = array_values($this->diskons);
                $this->saveToJson();
                return true;
            }
        }
        return false;
    }

    public function getDiskonByName($diskon_nama)
    {
        foreach ($this->diskons as $diskon) {
            if ($diskon->diskon_nama == $diskon_nama) {
                return $diskon;
            }
        }
        return null;
    }
}
