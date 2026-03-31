<?php

class AlatModel {
    private $table = 'alat';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllAlat() {
        $this->db->query("SELECT * FROM " . $this->table);
        return $this->db->resultSet();
    }

    // --- SEKARANG SUDAH OTOMATIS SIMPAN GAMBAR ---
    public function tambahDataAlat($data) {
        $query = "INSERT INTO alat (nama_alat, kategori, stok, gambar) 
                  VALUES (:nama_alat, :kategori, :stok, :gambar)";
        
        $this->db->query($query);
        $this->db->bind('nama_alat', $data['nama_alat']);
        $this->db->bind('kategori', $data['kategori']);
        $this->db->bind('stok', $data['stok']);
        $this->db->bind('gambar', $data['gambar']); // Ini kuncinya bro!

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAlatById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // --- SEKARANG SUDAH OTOMATIS UPDATE GAMBAR ---
    public function ubahDataAlat($data) {
        $query = "UPDATE alat SET 
                    nama_alat = :nama_alat, 
                    kategori = :kategori, 
                    stok = :stok, 
                    gambar = :gambar 
                  WHERE id = :id";
                  
        $this->db->query($query);
        $this->db->bind('nama_alat', $data['nama_alat']);
        $this->db->bind('kategori', $data['kategori']);
        $this->db->bind('stok', $data['stok']);
        $this->db->bind('gambar', $data['gambar']); // Nama file unik masuk sini
        $this->db->bind('id', $data['id']);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataAlat($id) {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id=:id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}