<?php

class AuthController extends Controller {

    public function index() {
        // Biar user yang sudah login nggak bisa balik ke halaman login lagi
        if (isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/AdminController/index');
            exit;
        }
        $data['judul'] = 'Login';
        $this->view('auth/login', $data); 
    }

    public function login() {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = $this->model('UserModel')->getUserByUsername($username);

        if ($user) {
            // Cek Password (Hash maupun teks biasa buat jaga-jaga)
            if (password_verify($password, $user['password']) || $password == $user['password']) {
                
                // MENGAKTIFKAN SESSION
                $_SESSION['login'] = true;
                
                // --- BAGIAN KRUSIAL: SIMPAN DATA KE SESSION ---
                $_SESSION['id_user']  = $user['id']; 
                $_SESSION['username'] = $user['username'];
                // Pastikan kolom ini sesuai dengan database lo ('nama' atau 'nama_lengkap')
                $_SESSION['nama']     = $user['nama']; 
                $_SESSION['role']     = strtolower($user['role']); 

                header('Location: ' . BASEURL . '/AdminController/index');
                exit;
            }
        }

        header('Location: ' . BASEURL . '/AuthController/index?status=gagal');
        exit;
    }

    public function logout() {
        if (!session_id()) session_start();
        $_SESSION = [];
        session_unset();
        session_destroy();
        
        header('Location: ' . BASEURL . '/AuthController/index');
        exit;
    }

    public function registrasi() {
        $data['judul'] = 'Daftar Akun';
        $this->view('auth/registrasi', $data);
    }

    public function prosesRegistrasi() {
        // 1. Cek apakah username sudah dipakai
        $cekUser = $this->model('UserModel')->getUserByUsername($_POST['username']);
        if($cekUser) {
             header('Location: ' . BASEURL . '/AuthController/registrasi?status=username_ada');
             exit;
        }

        // 2. Hash password sebelum simpan (Opsional tapi sangat disarankan)
        // $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // 3. Eksekusi daftar
        // Pastikan method di UserModel namanya 'tambahUser' atau 'tambahDataUser'
        if ($this->model('UserModel')->tambahUser($_POST) > 0) {
            header('Location: ' . BASEURL . '/AuthController/index?status=berhasil_daftar');
            exit;
        } else {
            header('Location: ' . BASEURL . '/AuthController/registrasi?status=gagal');
            exit;
        }
    }
}