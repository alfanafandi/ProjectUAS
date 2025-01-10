<?php
require_once __DIR__ . '/../domain_object/node_diskon.php';
require_once __DIR__ . '/../domain_object/node_restoran.php';

class DiskonModel
{
    private $db;

    public function __construct()
    {
        $this->db = new mysqli('localhost', 'root', '', 'ProjectDB');
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function addDiskon($restoran_id, $diskon_nama, $diskon_presentase)
    {
        $stmt = $this->db->prepare("INSERT INTO diskons (restoran_id, diskon_nama, diskon_presentase) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $restoran_id, $diskon_nama, $diskon_presentase);
        $stmt->execute();
        $stmt->close();
    }

    public function getDiskonsByRestoran($restoran_id)
    {
        $stmt = $this->db->prepare("SELECT d.*, r.restoran_nama FROM diskons d JOIN restorans r ON d.restoran_id = r.restoran_id WHERE d.restoran_id = ?");
        $stmt->bind_param("i", $restoran_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $diskons = [];
        while ($row = $result->fetch_assoc()) {
            $diskons[] = $row;
        }
        $stmt->close();

        return $diskons;
    }

    public function getDiskonById($diskon_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM diskons WHERE diskon_id = ?");
        $stmt->bind_param("i", $diskon_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $diskon = $result->fetch_assoc();
        $stmt->close();
        return $diskon;
    }

    public function getAllDiskons()
    {
        $result = $this->db->query("SELECT d.*, r.restoran_nama FROM diskons d JOIN restorans r ON d.restoran_id = r.restoran_id");
        $diskons = [];
        while ($row = $result->fetch_assoc()) {
            $diskons[] = $row;
        }
        return $diskons;
    }

    public function updateDiskon($diskon_id, $restoran_id, $diskon_nama, $diskon_presentase)
    {
        $stmt = $this->db->prepare("UPDATE diskons SET restoran_id = ?, diskon_nama = ?, diskon_presentase = ? WHERE diskon_id = ?");
        $stmt->bind_param("isii", $restoran_id, $diskon_nama, $diskon_presentase, $diskon_id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteDiskon($diskon_id)
    {
        $stmt = $this->db->prepare("DELETE FROM diskons WHERE diskon_id = ?");
        $stmt->bind_param("i", $diskon_id);
        $stmt->execute();
        $stmt->close();
    }
}
