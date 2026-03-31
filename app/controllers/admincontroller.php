<?php

class AdminController extends Controller {

    public function __construct() {
        // Cek login di awal
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/AuthController/index');
            exit;
        }

        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
        $url = explode('/', $url);
        $method = $url[1] ?? 'index';
        $role = strtolower($_SESSION['role'] ?? 'user');

        // --- KONFIGURASI HAK AKSES ---
        // Menu yang hanya bisa dibuka oleh Admin
        $khususAdmin = ['user', 'hapusUser', 'prosesTambahUser', 'getUserEdit', 'ubahUser']; 
        
        // Fitur yang hanya bisa dijalankan Admin & Petugas
        // UPDATE: Menambahkan 'tolak' ke whitelist agar tidak mental ke dashboard
        $adminPetugas = ['tambah', 'ubah', 'getubah', 'konfirmasi', 'hapus', 'laporan', 'tolak'];

        // Proteksi Admin Only
        if (in_array($method, $khususAdmin) && $role !== 'admin') {
            $this->setFlash('Akses Ditolak', 'Hanya Admin yang boleh kelola User!', 'danger');
            header('Location: ' . BASEURL . '/AdminController/index');
            exit;
        }

        // Proteksi Admin & Petugas
        if (in_array($method, $adminPetugas) && !in_array($role, ['admin', 'petugas'])) {
            $this->setFlash('Akses Ditolak', 'Anda tidak punya akses untuk aksi ini!', 'danger');
            header('Location: ' . BASEURL . '/AdminController/index');
            exit;
        }
    }

    private function setFlash($pesan, $aksi, $tipe) {
        $_SESSION['flash'] = ['pesan' => $pesan, 'aksi' => $aksi, 'tipe' => $tipe];
    }

    // ==========================================
    // --- DASHBOARD & VIEW ---
    // ==========================================

    public function index() {
        $role = strtolower($_SESSION['role'] ?? 'user');
        $id_user = $_SESSION['id_user'] ?? 0;

        $data['judul'] = 'Dashboard Overview';
        $data['total_alat'] = count($this->model('AlatModel')->getAllAlat());
        $data['total_user'] = count($this->model('UserModel')->getAllUser());
        
        if ($role === 'admin' || $role === 'petugas') {
            $transaksi = $this->model('TransaksiModel')->getAllTransaksi();
            $data['count_pinjam'] = count(array_filter($transaksi, function($t) {
                return isset($t['status']) && strtolower(trim($t['status'])) === 'dipinjam';
            }));
        } else {
            $transaksi = $this->model('TransaksiModel')->getTransaksiByUser($id_user);
            $data['my_loan'] = count(array_filter($transaksi, function($t) {
                return isset($t['status']) && strtolower(trim($t['status'])) === 'dipinjam';
            }));
        }

        $data['count_pending'] = count(array_filter($transaksi, function($t) {
            return isset($t['status']) && strtolower(trim($t['status'])) === 'pending';
        }));
        
        $data['alat'] = $this->model('AlatModel')->getAllAlat();
        $data['transaksi'] = $transaksi; 
        $this->view('admin/index', $data);
    }

    public function laporan() {
        $data['judul'] = 'Laporan Transaksi Global';
        $data['transaksi'] = $this->model('TransaksiModel')->getAllTransaksi();
        $this->view('admin/laporan', $data);
    }

    public function transaksi() {
        $data['judul'] = 'Monitoring Transaksi';
        $role = strtolower($_SESSION['role'] ?? 'user');
        $id_user = $_SESSION['id_user'] ?? 0;

        if ($role === 'admin' || $role === 'petugas') {
            $data['transaksi'] = $this->model('TransaksiModel')->getAllTransaksi();
        } else {
            $data['transaksi'] = $this->model('TransaksiModel')->getTransaksiByUser($id_user);
        }
        
        $data['alat'] = $this->model('AlatModel')->getAllAlat();
        $this->view('admin/transaksi', $data);
    }

    public function alat() {
        $data['judul'] = 'Katalog Alat'; 
        $data['alat'] = $this->model('AlatModel')->getAllAlat();
        $this->view('admin/alat', $data);
    }

    // ==========================================
    // --- KELOLA ALAT ---
    // ==========================================

    private function uploadGambar() {
        if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === 4) return null;

        $namaFile = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $tmpName = $_FILES['gambar']['tmp_name'];
        $ekstensiValid = ['jpg', 'jpeg', 'png'];
        $ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ekstensi, $ekstensiValid) || $ukuranFile > 2000000) return false; 

        $namaBaru = uniqid() . '.' . $ekstensi;
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/PEMINJAMAN ALAT/public/assets/img/'; 
        
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        return move_uploaded_file($tmpName, $targetDir . $namaBaru) ? $namaBaru : false;
    }

    public function tambah() {
        $gambar = $this->uploadGambar();
        if ($gambar === false) {
            $this->setFlash('Gagal', 'Gambar tidak valid (JPG/PNG, Max 2MB)', 'danger');
            header('Location: ' . BASEURL . '/AdminController/alat');
            exit;
        }

        $_POST['gambar'] = $gambar ?: 'default.jpg';

        if ($this->model('AlatModel')->tambahDataAlat($_POST) > 0) {
            $this->setFlash('Berhasil', 'Alat baru ditambahkan', 'success');
        } else {
            $this->setFlash('Gagal', 'Menambahkan data ke database', 'danger');
        }
        header('Location: ' . BASEURL . '/AdminController/alat');
        exit;
    }

    public function getubah($id) {
        echo json_encode($this->model('AlatModel')->getAlatById($id));
    }

    public function ubah() {
        if ($_FILES['gambar']['error'] === 4) {
            $_POST['gambar'] = $_POST['gambar_lama']; 
        } else {
            $gambar = $this->uploadGambar();
            if ($gambar) {
                $_POST['gambar'] = $gambar;
                $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/PEMINJAMAN ALAT/public/assets/img/' . $_POST['gambar_lama'];
                if (!empty($_POST['gambar_lama']) && file_exists($oldPath) && $_POST['gambar_lama'] != 'default.jpg') {
                    unlink($oldPath);
                }
            } else {
                $this->setFlash('Gagal', 'Format gambar salah atau terlalu besar', 'danger');
                header('Location: ' . BASEURL . '/AdminController/alat');
                exit;
            }
        }

        if ($this->model('AlatModel')->ubahDataAlat($_POST) > 0) {
            $this->setFlash('Berhasil', 'Data alat diubah', 'success');
        }
        header('Location: ' . BASEURL . '/AdminController/alat');
        exit;
    }

    public function hapus($id) {
        $data = $this->model('AlatModel')->getAlatById($id);
        if ($data && !empty($data['gambar']) && $data['gambar'] != 'default.jpg') {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/PEMINJAMAN ALAT/public/assets/img/' . $data['gambar'];
            if (file_exists($filePath)) unlink($filePath);
        }

        if ($this->model('AlatModel')->hapusDataAlat($id) > 0) {
            $this->setFlash('Berhasil', 'Alat dihapus', 'success');
        }
        header('Location: ' . BASEURL . '/AdminController/alat');
        exit;
    }

    // ==========================================
    // --- KELOLA TRANSAKSI ---
    // ==========================================

    public function ajukanPinjam() {
        $_POST['id_user'] = $_SESSION['id_user'];
        if ($this->model('TransaksiModel')->tambahDataTransaksi($_POST)) {
            $this->setFlash('Berhasil', 'Pinjaman diajukan', 'success');
        } else {
            $this->setFlash('Gagal', 'Stok habis', 'danger');
        }
        header('Location: ' . BASEURL . '/AdminController/transaksi');
        exit;
    }

    public function konfirmasi($id) {
        if ($this->model('TransaksiModel')->setujuiPinjam($id)) {
            $this->setFlash('Berhasil', 'Peminjaman disetujui', 'success');
        }
        header('Location: ' . BASEURL . '/AdminController/transaksi');
        exit;
    }

    // METHOD BARU: Menangani Penolakan Pinjaman
    public function tolak($id) {
        // Panggil model untuk proses tolak & balikin stok
        if ($this->model('TransaksiModel')->tolakPinjaman($id)) {
            $this->setFlash('Berhasil', 'Peminjaman ditolak', 'success');
        } else {
            $this->setFlash('Gagal', 'Gagal memproses penolakan', 'danger');
        }
        header('Location: ' . BASEURL . '/AdminController/transaksi');
        exit;
    }

    public function kembalikan($id) {
        $transaksi = $this->model('TransaksiModel')->getTransaksiById($id);
        
        if (!$transaksi) {
            $this->setFlash('Gagal', 'Data tidak ditemukan', 'danger');
            header('Location: ' . BASEURL . '/AdminController/transaksi');
            exit;
        }

        // --- PROTEKSI KEAMANAN: Cek apakah yang login adalah peminjam ---
        $role = strtolower($_SESSION['role'] ?? 'user');
        if ($role === 'user' && $_SESSION['id_user'] != $transaksi['id_user']) {
            $this->setFlash('Akses Ditolak', 'Hanya peminjam yang bisa mengembalikan alat ini!', 'danger');
            header('Location: ' . BASEURL . '/AdminController/transaksi');
            exit;
        }

        $tgl_pinjam = new DateTime($transaksi['tgl_pinjam']); 
        $tgl_sekarang = new DateTime(date('Y-m-d'));
        $diff = $tgl_pinjam->diff($tgl_sekarang)->days;
        
        // Aturan denda: Lebih dari 3 hari kena denda Rp 5.000 per hari
        $denda = ($diff > 3) ? ($diff - 3) * 5000 : 0;

        $data_update = [
            'id' => $id,
            'id_alat' => $transaksi['id_alat'],
            'denda' => $denda,
            'status' => 'Kembali'
        ];

        if ($this->model('TransaksiModel')->prosesKembali($data_update)) {
            $msg = ($denda > 0) ? "Denda: Rp " . number_format($denda, 0, ',', '.') : "Tepat waktu";
            $this->setFlash('Berhasil', "Alat dikembalikan! $msg", 'success');
        }
        header('Location: ' . BASEURL . '/AdminController/transaksi');
        exit;
    }

    // ==========================================
    // --- KELOLA USER ---
    // ==========================================

    public function user() {
        $data['judul'] = 'Manage User';
        $data['pengguna'] = $this->model('UserModel')->getAllUser(); 
        $this->view('admin/user', $data);
    }

    public function prosesTambahUser() {
        if ($this->model('UserModel')->tambahDataUser($_POST) > 0) {
            $this->setFlash('Berhasil', 'User baru terdaftar', 'success');
        }
        header('Location: ' . BASEURL . '/AdminController/user');
        exit;
    }

    public function ubahUser() {
        if ($this->model('UserModel')->ubahDataUser($_POST) > 0) {
            $this->setFlash('Berhasil', 'Data user diubah', 'success');
        }
        header('Location: ' . BASEURL . '/AdminController/user');
        exit;
    }

    public function hapusUser($id) {
        if ($this->model('UserModel')->hapusDataUser($id) > 0) {
            $this->setFlash('Berhasil', 'User dihapus', 'success');
        }
        header('Location: ' . BASEURL . '/AdminController/user');
        exit;
    }
}