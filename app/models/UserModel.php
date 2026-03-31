<?php

class UserModel {
    private $table = 'pengguna'; // Nama tabel di database
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllUser() {
        $this->db->query("SELECT * FROM " . $this->table);
        return $this->db->resultSet();
    }

    public function getUserById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getUserByUsername($username) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE username = :username");
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    // Ganti nama ke 'tambahUser' agar sinkron dengan AuthController
    public function tambahUser($data) {
        $query = "INSERT INTO " . $this->table . " (nama_lengkap, username, password, role) 
                  VALUES (:nama, :username, :password, :role)";
        
        $this->db->query($query);
        // Pakai key 'nama' sesuai dengan name di form registrasi
        $this->db->bind('nama', $data['nama']); 
        $this->db->bind('username', $data['username']);
        // Hashing password otomatis saat pendaftaran
        $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind('role', $data['role'] ?? 'user');

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubahDataUser($data) {
        $query = "UPDATE " . $this->table . " SET 
                    nama_lengkap = :nama, 
                    username = :username, 
                    role = :role";
        
        if (!empty($data['password'])) {
            $query .= ", password = :password";
        }

        $query .= " WHERE id = :id";
        
        $this->db->query($query);
        // Sesuaikan key array dengan input dari form management user
        $this->db->bind('nama', $data['nama_lengkap']);
        $this->db->bind('username', $data['username']);
        $this->db->bind('role', $data['role']);
        $this->db->bind('id', $data['id']);

        if (!empty($data['password'])) {
            $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        }

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}