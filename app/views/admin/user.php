<?php 
if (!isset($_SESSION['login'])) {
    header('Location: ' . BASEURL . '/AuthController/index');
    exit;
}

$role_user = strtolower($_SESSION['role'] ?? 'user');
$judul_halaman = $data['judul'] ?? 'Manajemen Pengguna';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul_halaman; ?> | SIPEMINJAM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { 
            --primary: #6366f1; 
            --primary-soft: #eef2ff;
            --sidebar-color: #0f172a; 
            --bg-body: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --card-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.04), 0 6px 10px -5px rgba(0, 0, 0, 0.02);
        }
        
        body { background-color: var(--bg-body); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-dark); overflow-x: hidden; }
        
        /* --- Sidebar --- */
        .sidebar { 
            height: 100vh !important; background-color: var(--sidebar-color) !important; 
            position: fixed !important; width: 280px; z-index: 1040 !important; 
            left: 0; top: 0; padding: 2rem 1.5rem; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; flex-direction: column; box-shadow: 10px 0 30px rgba(0, 0, 0, 0.03);
        }
        .sidebar.collapsed { left: -280px; }
        .sidebar-brand { color: #fff; font-size: 1.5rem; font-weight: 800; letter-spacing: -1px; margin-bottom: 2.5rem; padding: 0 1rem; }
        .sidebar-brand span { color: var(--primary); }

        .nav-link { 
            color: #94a3b8 !important; padding: 0.8rem 1.2rem !important; margin-bottom: 0.5rem; 
            border-radius: 14px; transition: 0.3s; display: flex !important; align-items: center; 
            font-weight: 600; text-decoration: none !important;
        }
        .nav-link:hover { color: #fff !important; background: rgba(255,255,255,0.05); transform: translateX(5px); }
        .nav-link.active { background: var(--primary) !important; color: #fff !important; box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4); }
        
        /* --- Content Wrapper --- */
        .content-wrapper { margin-left: 280px; padding: 2.5rem; transition: 0.4s; min-height: 100vh; position: relative; z-index: 1; }
        .content-wrapper.full-width { margin-left: 0; }
        
        /* --- Tables & Cards --- */
        .table-container { background: white; border-radius: 28px; padding: 1.5rem; border: 1px solid #f1f5f9; box-shadow: var(--card-shadow); }
        .stat-card-lux { background: white; border-radius: 24px; padding: 1.5rem; border: 1px solid #f1f5f9; box-shadow: var(--card-shadow); }
        
        .avatar-ui { width: 45px; height: 45px; border-radius: 14px; background: var(--primary-soft); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .badge-pill-lux { padding: 6px 14px; border-radius: 10px; font-weight: 700; font-size: 0.7rem; text-transform: uppercase; border: none; display: inline-block; }
        
        /* Warna Badge */
        .role-admin { background: #fee2e2; color: #ef4444; }
        .role-petugas { background: #fffbeb; color: #d97706; }
        .role-user { background: #f0fdf4; color: #16a34a; }

        .btn-action-lux { width: 38px; height: 38px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #f1f5f9; background: #fff; transition: 0.3s; }
        .btn-edit:hover { background: var(--primary-soft); color: var(--primary); }
        .btn-delete:hover { background: #fee2e2; color: #ef4444; }

        @media (max-width: 992px) {
            .sidebar { left: -280px; }
            .sidebar.active { left: 0; z-index: 1070 !important; }
            .content-wrapper { margin-left: 0; padding: 1.5rem; padding-top: 5rem; }
        }
    </style>
</head>
<body>

<div class="sidebar shadow-lg" id="sidebar">
    <div class="sidebar-brand">SIPEMINJAM<span>.</span></div>
    <div class="nav-container flex-grow-1">
        <nav class="nav flex-column">
            <a class="nav-link" href="<?= BASEURL; ?>/AdminController/index"><i class="fas fa-chart-pie me-2"></i> <span>Dashboard</span></a>
            <a class="nav-link" href="<?= BASEURL; ?>/AdminController/alat"><i class="fas fa-toolbox me-2"></i> <span>Data Alat</span></a>
            <a class="nav-link" href="<?= BASEURL; ?>/AdminController/transaksi"><i class="fas fa-clock-rotate-left me-2"></i> <span>Monitoring</span></a>
            <a class="nav-link active" href="<?= BASEURL; ?>/AdminController/user"><i class="fas fa-users-gear me-2"></i> <span>User Management</span></a>
        </nav>
    </div>
    <div class="mt-auto">
        <hr class="text-secondary opacity-25">
        <a class="nav-link text-danger" href="<?= BASEURL; ?>/AuthController/logout"><i class="fas fa-arrow-right-from-bracket me-2"></i> <span>Sign Out</span></a>
    </div>
</div>

<div class="content-wrapper" id="contentWrapper">
    <div class="row align-items-center mb-5 g-4">
        <div class="col-md-6 d-flex align-items-center">
            <button class="btn btn-white shadow-sm border-0 me-3 rounded-3 p-2" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <h2 class="fw-800 mb-0"><?= $judul_halaman; ?></h2>
                <p class="text-muted small mb-0">Kelola akses personil sistem Anda.</p>
            </div>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm tombolTambahUser" data-bs-toggle="modal" data-bs-target="#modalUser">
                <i class="fas fa-plus-circle me-2"></i> Register Member
            </button>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="stat-card-lux d-flex align-items-center gap-3">
                <div class="avatar-ui"><i class="fas fa-users"></i></div>
                <div>
                    <span class="text-muted small fw-600 d-block">TOTAL PERSONEL</span>
                    <h3 class="fw-800 mb-0"><?= count($data['pengguna'] ?? []); ?> <span class="text-muted fw-500 fs-6">Members</span></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted small fw-700 uppercase">
                        <th>Profil Member</th>
                        <th>Level Akses</th>
                        <th>Status</th>
                        <th class="text-end">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['pengguna'])) : foreach($data['pengguna'] as $u) : ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-ui shadow-sm"><?= strtoupper(substr($u['nama_lengkap'] ?? 'U', 0, 1)); ?></div>
                                <div>
                                    <div class="fw-700 mb-0"><?= $u['nama_lengkap']; ?></div>
                                    <div class="text-muted small">@<?= $u['username']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php 
                                // Deteksi Role (Jika kosong otomatis PENGGUNA)
                                $r = strtoupper($u['role'] ?? 'PENGGUNA');
                                if($r == '') $r = 'PENGGUNA';
                                
                                // Set Class Warna
                                $cls = 'role-user'; // Default
                                if($r == 'ADMIN') $cls = 'role-admin';
                                if($r == 'PETUGAS') $cls = 'role-petugas';
                            ?>
                            <span class="badge-pill-lux <?= $cls; ?>"><?= $r; ?></span>
                        </td>
                        <td><span class="text-success small fw-700"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> Aktif</span></td>
                        <td class="text-end">
                            <?php if($u['username'] != $_SESSION['username']) : ?>
                                <button class="btn-action-lux btn-edit tampilModalUbahUser" data-bs-toggle="modal" data-bs-target="#modalUser" data-id="<?= $u['id']; ?>">
                                    <i class="fas fa-pen text-primary"></i>
                                </button>
                                <a href="<?= BASEURL; ?>/AdminController/hapusUser/<?= $u['id']; ?>" class="btn-action-lux btn-delete" onclick="return confirm('Hapus personil ini?')">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                            <?php else : ?>
                                <span class="badge bg-light text-muted border">You</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else : ?>
                    <tr><td colspan="4" class="text-center py-5">No members found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 24px;">
            <div class="modal-header border-0 p-4">
                <h5 class="fw-800 mb-0" id="modalLabelUser">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASEURL; ?>/AdminController/tambahUser" method="post">
                <input type="hidden" name="id" id="id_user">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-muted">NAMA LENGKAP</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control bg-light border-0 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-muted">USERNAME</label>
                        <input type="text" name="username" id="username" class="form-control bg-light border-0 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-muted">PASSWORD</label>
                        <input type="password" name="password" id="password" class="form-control bg-light border-0 py-2">
                        <small id="passHelp" class="text-primary" style="display:none;">* Kosongkan jika tidak diubah</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-700 text-muted">HAK AKSES</label>
                        <select name="role" id="role" class="form-select bg-light border-0 py-2">
                            <option value="ADMIN">Administrator</option>
                            <option value="PETUGAS">Petugas Lapangan</option>
                            <option value="PENGGUNA">Regular User</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const contentWrapper = document.getElementById('contentWrapper');
    const sidebarToggle = document.getElementById('sidebarToggle');

    sidebarToggle.addEventListener('click', () => {
        if (window.innerWidth <= 992) {
            sidebar.classList.toggle('active');
        } else {
            sidebar.classList.toggle('collapsed');
            contentWrapper.classList.toggle('full-width');
        }
    });

    $(function() {
        $('.tombolTambahUser').on('click', function() {
            $('#modalLabelUser').html('Register Member Baru');
            $('#password').attr('required', true);
            $('#passHelp').hide();
            $('.modal-content form').attr('action', '<?= BASEURL; ?>/AdminController/tambahUser');
            $('#id_user').val('');
            $('#nama_lengkap').val('');
            $('#username').val('');
            $('#role').val('PENGGUNA');
        });

        $('.tampilModalUbahUser').on('click', function() {
            $('#modalLabelUser').html('Update Profile Personil');
            $('#password').attr('required', false);
            $('#passHelp').show();
            $('.modal-content form').attr('action', '<?= BASEURL; ?>/AdminController/ubahUser');

            const id = $(this).data('id');
            $.ajax({
                url: '<?= BASEURL; ?>/AdminController/getUserEdit/' + id,
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#nama_lengkap').val(data.nama_lengkap);
                    $('#username').val(data.username);
                    $('#role').val(data.role.toUpperCase());
                    $('#id_user').val(data.id);
                }
            });
        });
    });
</script>
</body>
</html>