<?php 
if (!isset($_SESSION['login'])) {
    header('Location: ' . BASEURL . '/AuthController/index');
    exit;
}

// Mengambil data dari controller dengan fallback nilai 0/array kosong
$total_alat = $data['total_alat'] ?? 0;
$count_pinjam = $data['count_pinjam'] ?? 0;
$total_user = $data['total_user'] ?? 0;
$my_loan = $data['my_loan'] ?? 0; 
$daftar_alat = $data['alat'] ?? [];
$transaksi_terbaru = $data['transaksi'] ?? []; // Mengambil data transaksi
$judul_halaman = $data['judul'] ?? 'Dashboard';
$role_user = strtolower($_SESSION['role'] ?? 'user');
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
    <style>
        :root {
            --sidebar-bg: #0f172a;
            --accent: #6366f1;
            --bg-body: #f8fafc;
            --card-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.04), 0 6px 10px -5px rgba(0, 0, 0, 0.02);
        }

        body { background-color: var(--bg-body); font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; overflow-x: hidden; }

        /* --- SIDEBAR UPGRADED --- */
        .sidebar { 
            height: 100vh !important; 
            background-color: var(--sidebar-bg) !important; 
            position: fixed !important; 
            width: 280px; 
            z-index: 1040 !important; 
            left: 0; 
            top: 0; 
            padding: 2rem 1.5rem; 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; 
            flex-direction: column;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.03);
        }
        .sidebar.collapsed { left: -280px; }
        .sidebar-brand { padding: 0 1rem; color: #fff; font-size: 1.5rem; font-weight: 800; letter-spacing: -1px; margin-bottom: 2.5rem; }
        .sidebar-brand span { color: var(--accent); }
        
        .nav-link { 
            color: #94a3b8 !important; padding: 0.8rem 1.2rem !important; margin-bottom: 0.5rem; 
            border-radius: 14px; transition: 0.3s; display: flex !important; align-items: center; 
            font-weight: 600; text-decoration: none !important;
        }
        .nav-link:hover { color: #fff !important; background: rgba(255,255,255,0.05); transform: translateX(5px); }
        .nav-link.active { background: var(--accent) !important; color: #fff !important; box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4); }

        /* --- CONTENT WRAPPER --- */
        .content-wrapper { margin-left: 280px; padding: 2.5rem; transition: 0.4s; min-height: 100vh; position: relative; z-index: 1; }
        .content-wrapper.full-width { margin-left: 0; }

        /* --- CARD STATS --- */
        .card-stat { border: none; border-radius: 24px; padding: 1.8rem; color: white; position: relative; overflow: hidden; height: 100%; transition: 0.4s; }
        .bg-grad-1 { background: linear-gradient(135deg, #6366f1, #818cf8); }
        .bg-grad-2 { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        .bg-grad-3 { background: linear-gradient(135deg, #10b981, #34d399); }
        
        /* --- TOOL CARD UPGRADED --- */
        .tool-card {
            border: 1px solid rgba(0,0,0,0.05) !important;
            border-radius: 28px !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: #ffffff;
        }
        .tool-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        }
        .card-icon-container {
            height: 180px;
            background: #f8fafc;
            border-radius: 22px;
            padding: 15px;
            margin-bottom: 15px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .tool-card img { transition: 0.5s ease; width: 100%; height: 100%; object-fit: contain; }
        .tool-card:hover img { transform: scale(1.1); }

        .badge-stok {
            font-size: 0.75rem; padding: 6px 12px; border-radius: 10px;
            background: rgba(99, 102, 241, 0.08); color: var(--accent); font-weight: 700;
        }

        .btn-detail-alat {
            border: 1.5px solid #e2e8f0; color: #64748b; font-weight: 700;
            border-radius: 14px !important; padding: 10px; transition: 0.3s; background: transparent;
        }
        .tool-card:hover .btn-detail-alat {
            background: var(--accent); color: white; border-color: var(--accent);
            box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3);
        }

        .role-badge { background: rgba(99, 102, 241, 0.1); color: var(--accent); font-weight: 800; font-size: 0.7rem; padding: 5px 12px; border-radius: 8px; text-transform: uppercase; }
        .item-hidden { display: none !important; }

        /* Modal Fix */
        .modal { z-index: 1060 !important; }
        .modal-backdrop { z-index: 1050 !important; backdrop-filter: blur(4px); }

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
            <a class="nav-link active" href="<?= BASEURL; ?>/AdminController/index">
                <i class="fas fa-chart-pie me-2"></i> <span>Dashboard</span>
            </a>
            <a class="nav-link" href="<?= BASEURL; ?>/AdminController/alat">
                <i class="fas fa-toolbox me-2"></i> <span><?= (in_array($role_user, ['admin', 'petugas'])) ? 'Manage Alat' : 'Katalog Alat'; ?></span>
            </a>
            <a class="nav-link" href="<?= BASEURL; ?>/AdminController/transaksi">
                <i class="fas fa-clock-rotate-left me-2"></i> <span>Monitoring</span>
            </a>

            <?php if (in_array($role_user, ['admin', 'petugas'])) : ?>
            <a class="nav-link" href="<?= BASEURL; ?>/AdminController/laporan">
                <i class="fas fa-file-export me-2"></i> <span>Laporan</span>
            </a>
            <?php endif; ?>

            <?php if ($role_user == 'admin') : ?>
            <a class="nav-link" href="<?= BASEURL; ?>/AdminController/user">
                <i class="fas fa-users-gear me-2"></i> <span>User Management</span>
            </a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="mt-auto">
        <hr class="text-secondary opacity-25">
        <a class="nav-link text-danger" href="<?= BASEURL; ?>/AuthController/logout">
            <i class="fas fa-arrow-right-from-bracket me-2"></i> <span>Sign Out</span>
        </a>
    </div>
</div>

<div class="content-wrapper" id="contentWrapper">
    <?php if(isset($_SESSION['flash'])) : ?>
        <div class="alert alert-<?= $_SESSION['flash']['tipe']; ?> alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
            <strong><?= $_SESSION['flash']['pesan']; ?></strong> <?= $_SESSION['flash']['aksi']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="row align-items-center mb-5 g-4">
        <div class="col-md-6 d-flex align-items-center">
            <button class="btn btn-white shadow-sm border-0 me-3 rounded-3 p-2" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <span class="role-badge mb-2 d-inline-block"><?= $role_user; ?> Account</span>
                <h2 class="fw-800 mb-0"><?= $judul_halaman; ?></h2>
            </div>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="search-container d-inline-flex align-items-center w-100" style="max-width: 350px; background: #fff; border-radius: 16px; padding: 6px 18px; box-shadow: var(--card-shadow);">
                <i class="fas fa-search text-muted me-2"></i>
                <input type="text" id="cariAlat" class="form-control border-0 shadow-none" placeholder="Cari inventaris...">
            </div>
        </div>
    </div>

    <?php if (in_array($role_user, ['admin', 'petugas'])) : ?>
        <div class="row g-4 mb-5">
            <div class="col-xl-4 col-md-6">
                <div class="card card-stat bg-grad-1 shadow-sm">
                    <p class="mb-1 fw-semibold opacity-75">Total Asset</p>
                    <h2 class="fw-800 mb-0"><?= $total_alat; ?> <small class="fs-6 opacity-75">Items</small></h2>
                    <i class="fas fa-boxes-stacked big-icon" style="position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.15; transform: rotate(-15deg);"></i>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-stat bg-grad-2 shadow-sm">
                    <p class="mb-1 fw-semibold opacity-75">On Loan (Global)</p>
                    <h2 class="fw-800 mb-0"><?= $count_pinjam; ?> <small class="fs-6 opacity-75">Items</small></h2>
                    <i class="fas fa-hand-holding-heart big-icon" style="position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.15; transform: rotate(-15deg);"></i>
                </div>
            </div>
            <div class="col-xl-4 col-md-12">
                <div class="card card-stat bg-grad-3 shadow-sm">
                    <p class="mb-1 fw-semibold opacity-75">Active Users</p>
                    <h2 class="fw-800 mb-0"><?= $total_user; ?> <small class="fs-6 opacity-75">Verified</small></h2>
                    <i class="fas fa-user-check big-icon" style="position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.15; transform: rotate(-15deg);"></i>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="row mb-5 g-4">
            <div class="col-xl-8 col-md-7">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 24px; background: linear-gradient(135deg, #1e293b, #334155); color: white;">
                    <div class="d-flex align-items-center h-100">
                        <div>
                            <h3 class="fw-800 mb-2">Halo, <?= $_SESSION['nama'] ?? 'User'; ?>! 👋</h3>
                            <p class="opacity-75 mb-4">Butuh alat untuk pekerjaanmu hari ini? Cek katalog di bawah.</p>
                            <a href="<?= BASEURL; ?>/AdminController/alat" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm fw-bold">
                                <i class="fas fa-plus me-2"></i>Pinjam Alat Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-5">
                <div class="card card-stat bg-grad-2 shadow-sm">
                    <p class="mb-1 fw-semibold opacity-75">Alat Kamu Pinjam</p>
                    <h2 class="fw-800 mb-0"><?= $my_loan; ?> <small class="fs-6 opacity-75">Items Active</small></h2>
                    <p class="small mt-2 mb-0 opacity-75">*Segera kembalikan jika sudah selesai.</p>
                    <i class="fas fa-stopwatch big-icon" style="position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.15; transform: rotate(-15deg);"></i>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-800 m-0">Quick View Inventory</h4>
        <a href="<?= BASEURL; ?>/AdminController/alat" class="text-primary fw-bold text-decoration-none small">Lihat Katalog <i class="fas fa-arrow-right ms-1"></i></a>
    </div>

    <div class="row g-4 mb-5" id="daftarAlat">
        <?php if(!empty($daftar_alat)) : 
            $i = 0;
            foreach($daftar_alat as $a) : 
                $i++;
                $initial_display = ($i > 4) ? 'item-hidden' : '';
        ?>
            <div class="col-xl-3 col-lg-4 col-md-6 item-alat <?= $initial_display; ?>" data-default-view="<?= ($i <= 4) ? 'true' : 'false'; ?>">
                <div class="card tool-card p-3 shadow-none h-100">
                    <div class="card-icon-container">
                        <img src="<?= BASEURL; ?>/assets/img/<?= !empty($a['gambar']) ? $a['gambar'] : 'default.jpg'; ?>" alt="<?= $a['nama_alat']; ?>">
                    </div>
                    
                    <div class="px-2 pb-2">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-800 nama-alat mb-0 text-truncate" style="max-width: 140px;"><?= htmlspecialchars($a['nama_alat']); ?></h6>
                            <span class="badge-stok">
                                <i class="fas fa-box me-1"></i><?= $a['stok']; ?>
                            </span>
                        </div>
                        <p class="text-muted small mb-3">Tersedia untuk dipinjam.</p>
                        <a href="<?= BASEURL; ?>/AdminController/alat" class="btn btn-detail-alat w-100 d-flex align-items-center justify-content-center">
                            <span>Detail Alat</span>
                            <i class="fas fa-chevron-right ms-2 small"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; else : ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada data alat yang tersedia.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 28px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-800 m-0">Aktivitas Terbaru</h4>
                    <a href="<?= BASEURL; ?>/AdminController/transaksi" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr class="text-muted small">
                                <th class="ps-3">Peminjam</th>
                                <th>Alat</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($transaksi_terbaru)) : 
                                // Hanya tampilkan 5 transaksi terakhir di dashboard
                                $limit_transaksi = array_slice($transaksi_terbaru, 0, 5);
                                foreach($limit_transaksi as $t) : 
                            ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 text-primary fw-bold" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                            <?= strtoupper(substr($t['nama_lengkap'] ?? 'U', 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-0" style="font-size: 0.9rem;"><?= $t['nama_lengkap']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-600 text-dark"><?= $t['nama_alat']; ?></span></td>
                                <td class="small text-muted"><?= date('d/m/Y', strtotime($t['tgl_pinjam'])); ?></td>
                                <td>
                                    <?php if (strtolower($t['status']) === 'kembali' && !empty($t['tanggal_kembali'])) : ?>
                                        <span class="text-success fw-bold small">
                                            <i class="fas fa-check-circle me-1"></i>
                                            <?= date('d/m/Y', strtotime($t['tanggal_kembali'])); ?>
                                        </span>
                                    <?php elseif (strtolower($t['status']) === 'ditolak') : ?>
                                        <span class="text-danger small opacity-75">
                                            <i class="fas fa-times-circle me-1"></i> Ditolak
                                        </span>
                                    <?php else : ?>
                                        <span class="text-warning small italic opacity-75">
                                            <i class="fas fa-clock me-1"></i> Belum Kembali
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $status = strtolower($t['status']);
                                        $badge_class = 'bg-secondary';
                                        if($status == 'pending') $badge_class = 'bg-warning text-dark';
                                        if($status == 'dipinjam') $badge_class = 'bg-info text-white';
                                        if($status == 'kembali') $badge_class = 'bg-success text-white';
                                        if($status == 'ditolak') $badge_class = 'bg-danger text-white';
                                    ?>
                                    <span class="badge <?= $badge_class; ?> rounded-pill px-3" style="font-size: 0.7rem; text-transform: uppercase;">
                                        <?= $status; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">Tidak ada aktivitas transaksi terbaru.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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

    // --- SEARCH LOGIC (RETAINED) ---
    const searchInput = document.getElementById('cariAlat');
    const container = document.getElementById('daftarAlat');

    if(searchInput) {
        searchInput.addEventListener('input', function() {
            let keyword = this.value.toLowerCase().trim();
            let items = document.querySelectorAll('.item-alat');
            let matched = 0;

            items.forEach(card => {
                let nama = card.querySelector('.nama-alat').innerText.toLowerCase();
                let isDefault = card.getAttribute('data-default-view') === 'true';
                
                if(keyword === '') {
                    if(isDefault) {
                        card.classList.remove('item-hidden');
                        card.style.display = 'block';
                    } else {
                        card.classList.add('item-hidden');
                        card.style.display = 'none';
                    }
                } else {
                    if(nama.includes(keyword)) {
                        card.classList.remove('item-hidden');
                        card.style.display = 'block';
                        matched++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });

            let noResult = document.getElementById('search-not-found');
            if(matched === 0 && keyword !== '') {
                if(!noResult) {
                    let msg = document.createElement('div');
                    msg.id = 'search-not-found';
                    msg.className = 'col-12 text-center py-5';
                    msg.innerHTML = '<i class="fas fa-search fa-2x text-muted mb-2"></i><p class="text-muted">Alat tidak ditemukan</p>';
                    container.appendChild(msg);
                }
            } else {
                if(noResult) noResult.remove();
            }
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                let keyword = this.value.trim();
                if(keyword !== '') {
                    window.location.href = "<?= BASEURL; ?>/AdminController/alat?search=" + encodeURIComponent(keyword);
                }
            }
        });
    }

    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 992 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });
</script>
</body>
</html>