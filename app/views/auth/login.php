<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman</title>
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/bootstrap.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: #f8f9fa; display: flex; align-items: center; height: 100vh; }
        .card-login { width: 100%; max-width: 400px; margin: auto; border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="card card-login p-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary">SIPEMINJAM</h3>
        <p class="text-muted">Silahkan login ke akun Anda</p>
    </div>

    <?php Flasher::flash(); ?>

    <form action="<?= BASEURL; ?>/auth/prosesLogin" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Masuk Sekarang</button>
    </form>
    
    <div class="text-center mt-4">
        <small class="text-muted">&copy; 2024 Aplikasi Peminjaman Alat</small>
    </div>
</div>

</body>
</html>