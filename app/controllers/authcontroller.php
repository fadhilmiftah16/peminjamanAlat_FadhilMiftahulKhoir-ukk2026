<?php
class AuthController extends Controller {

    public function index() {
        $data['judul'] = 'Login';
        $this->view('auth/login', $data);
    }

    public function prosesLogin() {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = $this->model('User')->getUserByUsername($username);

        if ($user) {
            // Verifikasi Password (Jika di database di-hash pakai password_hash)
            if (password_verify($password, $user['password'])) {
                
                // Simpan data ke Session
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('Location: ' . BASEURL . '/peminjaman/index');
                exit;
            } else {
                Flasher::setFlash('Password salah!', 'Login Gagal', 'error');
                header('Location: ' . BASEURL . '/auth');
                exit;
            }
        } else {
            Flasher::setFlash('Username tidak ditemukan!', 'Login Gagal', 'error');
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASEURL . '/auth');
        exit;
    }
}