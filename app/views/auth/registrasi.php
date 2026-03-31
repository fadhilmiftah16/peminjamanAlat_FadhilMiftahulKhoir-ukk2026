<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - SIPEMINJAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background: #0f172a; /* Gue samain sama sidebar biar estetik */
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex; align-items: center; justify-content: center; 
            min-height: 100vh; margin: 0;
        }
        .reg-card { 
            width: 100%; max-width: 450px; 
            border: none; border-radius: 24px; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            background: #ffffff;
        }
        .form-control { border-radius: 12px; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
        .btn-reg { 
            border-radius: 12px; padding: 12px; font-weight: 700; 
            background: #6366f1; border: none; color: white; transition: 0.3s;
        }
        .btn-reg:hover { background: #4f46e5; transform: translateY(-2px); color: white; }
    </style>
</head>
<body>
    <div class="card reg-card p-4 my-5">
        <div class="card-body">
            <div class="text-center mb-4">
                <h2 class="fw-800 m-0" style="letter-spacing: -1px; color: #1e293b;">DAFTAR AKUN</h2>
                <p class="text-muted small">Bergabunglah dengan SIPEMINJAM.</p>
            </div>
            
            <form action="<?= BASEURL; ?>/AuthController/prosesRegistrasi" method="post">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama sesuai identitas" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Buat username unik" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                
                <input type="hidden" name="role" value="user">

                <button type="submit" class="btn btn-reg w-100 mb-3 shadow-sm">Buat Akun Sekarang</button>
            </form>
            
            <div class="text-center">
                <p class="small text-muted">Sudah punya akun? <a href="<?= BASEURL; ?>/AuthController/index" class="text-primary text-decoration-none fw-bold">Login di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>