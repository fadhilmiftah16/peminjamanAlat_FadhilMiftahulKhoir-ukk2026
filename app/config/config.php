<?php

/**
 * KONFIGURASI APLIKASI PEMINJAMAN ALAT (BALIK KE AWAL)
 */

// 1. URL Dasar website
// Pakai %20 lagi karena folder di htdocs balik jadi "PEMINJAMAN ALAT"
define('BASEURL', 'http://localhost/PEMINJAMAN%20ALAT/public');

// 2. Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'peminjaman_alat');

/**
 * TIPS: 
 * 1. Pastikan nama folder di C:/xampp/htdocs sudah lo balikin jadi "PEMINJAMAN ALAT"
 * 2. Restart Apache di XAMPP Control Panel biar gak nyangkut.
 */