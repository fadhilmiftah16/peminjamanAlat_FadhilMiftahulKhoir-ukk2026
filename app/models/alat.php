<?php
class Alat {
    private $table = 'alat';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllAlat() {
        $this->db->query('SELECT alat.*, kategori.nama_kategori FROM ' . $this->table . ' JOIN kategori ON alat.id_kategori = kategori.id_kategori');
        return $this->db->resultSet();
    }
}