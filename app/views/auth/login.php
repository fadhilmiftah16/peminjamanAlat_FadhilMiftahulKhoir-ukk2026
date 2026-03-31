<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPEMINJAM UKK</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --glass-bg: rgba(15, 23, 42, 0.8);
            --accent: #8b5cf6;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #020617;
            background-image: radial-gradient(circle at 50% -20%, #1e1b4b, #020617);
            height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; color: #fff;
        }
        .login-box {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 32px;
            padding: 3rem; width: 100%; max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }
        .form-control {
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px; padding: 12px 16px; color: #fff; transition: 0.3s;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.1); border-color: var(--accent); box-shadow: none; color: #fff;
        }
        .btn-login {
            background: var(--accent); border: none; border-radius: 16px;
            padding: 14px; font-weight: 700; width: 100%; margin-top: 1.5rem;
            transition: 0.3s; color: white;
        }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3); }
        .uk-badge {
            background: rgba(139, 92, 246, 0.2); color: var(--accent);
            padding: 5px 15px; border-radius: 20px; font-size: 11px; font-weight: 700;
            text-transform: uppercase; margin-bottom: 1rem; display: inline-block;
        }
        .reg-link { color: var(--accent); text-decoration: none; font-weight: 600; transition: 0.3s; }
        .reg-link:hover { color: #a78bfa; text-shadow: 0 0 8px rgba(139, 92, 246, 0.5); }
    </style>
</head>
<body>

<div class="login-box text-center">
    <span class="uk-badge">UKK RPL 2025/2026</span>
    <h2 class="fw-bold mb-1">SIPEMINJAM</h2>
    <p class="text-secondary small mb-4">Pengembangan Aplikasi Peminjaman Alat</p>

    <form action="<?= BASEURL; ?>/AuthController/login" method="post">
        <div class="text-start mb-3">
            <label class="small fw-semibold mb-2 ms-2 text-secondary">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Input username..." required>
        </div>
        <div class="text-start mb-4">
            <label class="small fw-semibold mb-2 ms-2 text-secondary">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-login shadow-lg">Sign In Account</button>
    </form>
    
    <div class="mt-4">
        <p class="small text-secondary">Belum punya akses? 
            <a href="<?= BASEURL; ?>/AuthController/registrasi" class="reg-link">Buat Akun Baru</a>
        </p>
    </div>
    
    <div class="mt-4 pt-3 border-top border-secondary opacity-25">
        <p class="small mb-0">Paket 1 - Konsentrasi Keahlian RPL</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Menangkap parameter status dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // Logic Alert berdasarkan status redirect dari Controller
    if (status === 'berhasil_daftar') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Daftar!',
            text: 'Akun lo udah aktif, silakan login bro.',
            background: '#0f172a',
            color: '#fff',
            confirmButtonColor: '#8b5cf6'
        });
    } else if (status === 'gagal') {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Username atau Password lo salah nih.',
            background: '#0f172a',
            color: '#fff',
            confirmButtonColor: '#8b5cf6'
        });
    } else if (status === 'username_ada') {
        Swal.fire({
            icon: 'warning',
            title: 'Username Terpakai',
            text: 'Cari username lain bro, yang itu udah ada yang punya.',
            background: '#0f172a',
            color: '#fff',
            confirmButtonColor: '#8b5cf6'
        });
    }
</script>

</body>
</html>