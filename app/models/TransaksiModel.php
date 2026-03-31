<?php 

class TransaksiModel {
    private $table = 'transaksi';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllTransaksi() {
        $query = "SELECT t.*, a.nama_alat, p.nama_lengkap 
                  FROM " . $this->table . " t
                  JOIN alat a ON t.id_alat = a.id
                  JOIN pengguna p ON t.id_user = p.id 
                  ORDER BY t.id DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getTransaksiByUser($id_user) {
        $query = "SELECT t.*, a.nama_alat, p.nama_lengkap 
                  FROM " . $this->table . " t
                  JOIN alat a ON t.id_alat = a.id
                  JOIN pengguna p ON t.id_user = p.id 
                  WHERE t.id_user = :id_user
                  ORDER BY t.id DESC";
        $this->db->query($query);
        $this->db->bind('id_user', $id_user);
        return $this->db->resultSet();
    }

    public function getTransaksiById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahDataTransaksi($data) {
        $this->db->query("SELECT stok FROM alat WHERE id = :id");
        $this->db->bind('id', $data['id_alat']);
        $alat = $this->db->single();

        if (!$alat || $alat['stok'] <= 0) return false;

        $query = "INSERT INTO " . $this->table . " (id_user, id_alat, tgl_pinjam, status, denda) 
                  VALUES (:id_user, :id_alat, :tgl_pinjam, :status, :denda)";
        
        $this->db->query($query);
        $this->db->bind('id_user', $_SESSION['id_user']); 
        $this->db->bind('id_alat', $data['id_alat']);
        $this->db->bind('tgl_pinjam', date('Y-m-d H:i:s')); 
        $this->db->bind('status', 'pending'); 
        $this->db->bind('denda', 0); 
        
        if($this->db->execute()) {
            $this->db->query("UPDATE alat SET stok = stok - 1 WHERE id = :id_alat");
            $this->db->bind('id_alat', $data['id_alat']);
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function setujuiPinjam($id) {
        $this->db->query("UPDATE " . $this->table . " SET status = 'dipinjam' WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function prosesKembali($data) {
        $id = $data['id'];
        $query = "UPDATE " . $this->table . " SET 
                    status = :status, 
                    tanggal_kembali = :tgl_kembali,
                    denda = :denda 
                  WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->bind('status', 'kembali');
        $this->db->bind('tgl_kembali', date('Y-m-d H:i:s'));
        $this->db->bind('denda', $data['denda']); 
        
        if($this->db->execute()) {
            $this->db->query("UPDATE alat SET stok = stok + 1 WHERE id = :id_alat");
            $this->db->bind('id_alat', $data['id_alat']);
            $this->db->execute();
            return true;
        }
        return false;
    }

    // --- METHOD BARU UNTUK TOLAK PINJAMAN ---
    public function tolakPinjaman($id) {
        // 1. Cari dulu ID alatnya buat balikin stok
        $this->db->query("SELECT id_alat FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        $transaksi = $this->db->single();

        if($transaksi) {
            // 2. Update status jadi ditolak
            $this->db->query("UPDATE " . $this->table . " SET status = 'ditolak' WHERE id = :id");
            $this->db->bind('id', $id);
            
            if($this->db->execute()) {
                // 3. Balikin stok alatnya
                $this->db->query("UPDATE alat SET stok = stok + 1 WHERE id = :id_alat");
                $this->db->bind('id_alat', $transaksi['id_alat']);
                return $this->db->execute();
            }
        }
        return false;
    }
}