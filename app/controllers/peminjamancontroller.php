<?php
class PeminjamanController extends Controller {
    public function index() {
        $data['judul'] = 'Daftar Alat';
        $data['alat'] = $this->model('Alat')->getAllAlat();
        $this->view('peminjam/index', $data);
    }
}