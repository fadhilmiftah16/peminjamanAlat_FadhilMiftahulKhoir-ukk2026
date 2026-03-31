<?php
// 1. Memulai session (Wajib di baris paling atas untuk fitur login)
if( !session_id() ) session_start();

// 2. Sesuaikan URL ini dengan folder project Anda
define('BASEURL', 'http://localhost/PEMINJAMAN%20ALAT/public');

// 3. Panggil file-file inti (Core)
// Pastikan nama file di bawah ini sama persis dengan yang ada di folder core kamu
require_once '../app/core/app.php';        
require_once '../app/core/controller.php'; 

// 4. Panggil file koneksi database
require_once '../app/config/database.php';

// 5. Jalankan aplikasi
$app = new App;