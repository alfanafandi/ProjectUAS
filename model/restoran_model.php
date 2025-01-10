<?php
require_once __DIR__ . '/../domain_object/node_restoran.php';

class RestoranModel
{
    private $db;

    public function __construct()
    {
        $this->db = new mysqli('localhost', 'root', '', 'ProjectDB');
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function addRestoran($restoran_nama, $restoran_password, $restoran_gambar)
    {
        $stmt = $this->db->prepare("INSERT INTO restorans (restoran_nama, restoran_password, restoran_gambar) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $restoran_nama, $restoran_password, $restoran_gambar);
        $stmt->execute();
        $stmt->close();
    }

    public function getAllRestorans()
    {
        $result = $this->db->query("SELECT * FROM restorans");
        $restorans = [];
        while ($row = $result->fetch_assoc()) {
            $restorans[] = new Restoran($row['restoran_id'], $row['restoran_nama'], $row['restoran_password'], $row['restoran_gambar']);
        }
        return $restorans;
    }

    public function getRestoranById($restoran_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM restorans WHERE restoran_id = ?");
        $stmt->bind_param("i", $restoran_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $restoran = $result->fetch_assoc();
        $stmt->close();
        return $restoran ? new Restoran($restoran['restoran_id'], $restoran['restoran_nama'], $restoran['restoran_password'], $restoran['restoran_gambar']) : null;
    }

    public function updateRestoran($restoran_id, $restoran_nama, $restoran_password, $restoran_gambar)
    {
        $stmt = $this->db->prepare("UPDATE restorans SET restoran_nama = ?, restoran_password = ?, restoran_gambar = ? WHERE restoran_id = ?");
        $stmt->bind_param("sssi", $restoran_nama, $restoran_password, $restoran_gambar, $restoran_id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteRestoran($restoran_id)
    {
        $stmt = $this->db->prepare("DELETE FROM restorans WHERE restoran_id = ?");
        $stmt->bind_param("i", $restoran_id);
        $stmt->execute();
        $stmt->close();
    }

    public function getRestoranByName($restoran_nama)
    {
        $stmt = $this->db->prepare("SELECT * FROM restorans WHERE restoran_nama = ?");
        $stmt->bind_param("s", $restoran_nama);
        $stmt->execute();
        $result = $stmt->get_result();
        $restoran = $result->fetch_assoc();
        $stmt->close();
        return $restoran ? new Restoran($restoran['restoran_id'], $restoran['restoran_nama'], $restoran['restoran_password'], $restoran['restoran_gambar']) : null;
    }
}
