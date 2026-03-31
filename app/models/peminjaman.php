<?php 

class peminjaman {
    private $table = 'peminjaman';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllPeminjaman() {
        // PERBAIKAN: Pakai tabel 'pengguna'
        // Kolom kunci adalah 'id_user' di peminjaman 
        // dan 'id' di pengguna
        $query = "SELECT p.*, u.nama_lengkap, a.nama_alat 
                  FROM " . $this->table . " p
                  JOIN pengguna u ON p.id_user = u.id 
                  JOIN alat a ON p.id_alat = a.id_alat 
                  ORDER BY p.tanggal_pinjam DESC";
        
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function updateStatus($id, $status) {
        // Gunakan id_peminjaman sebagai primary key
        $this->db->query("UPDATE " . $this->table . " SET status = :status WHERE id_peminjaman = :id");
        $this->db->bind('status', $status);
        $this->db->bind('id', $id);
        
        return $this->db->execute();
    }
}