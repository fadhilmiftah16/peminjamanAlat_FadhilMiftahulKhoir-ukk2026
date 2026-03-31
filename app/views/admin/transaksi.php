<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { 
            --primary: #6366f1; 
            --bg-body: #f8fafc;
            --sidebar: #0f172a;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }
        
        body { background-color: var(--bg-body); font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; overflow-x: hidden; }
        
        .sidebar { 
            height: 100vh; background-color: var(--sidebar); 
            position: fixed; width: 260px; z-index: 99999 !important; 
            left: 0; top: 0; padding: 1.5rem; transition: 0.3s; 
        }
        .sidebar-brand { color: #fff; font-size: 1.4rem; font-weight: 800; letter-spacing: -0.5px; padding-bottom: 2rem; display: block; text-decoration: none; text-align: center; line-height: 1.2; }
        .role-badge { font-size: 0.65rem; background: rgba(255,255,255,0.1); padding: 4px 12px; border-radius: 50px; color: var(--info); font-weight: 800; letter-spacing: 1px; }
        
        .nav-link { 
            color: #94a3b8 !important; padding: 0.8rem 1rem !important; 
            margin-bottom: 0.5rem; border-radius: 12px; display: flex; 
            align-items: center; transition: 0.3s; font-weight: 500; 
            text-decoration: none !important; cursor: pointer !important;
        }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1) !important; color: #ffffff !important; }
        .nav-link.active { background: var(--primary) !important; }

        .content-wrapper { 
            margin-left: 260px; padding: 2.5rem; transition: 0.3s; position: relative; z-index: 1; 
        }
        
        .table-container { background: #302b45; border-radius: 24px; padding: 1.5rem; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        .table thead th { background: transparent; border: none; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; padding: 1rem; }
        .table td { padding: 1.2rem 1rem; vertical-align: middle; border-bottom: 1px solid #191b1d; }
        
        .status-badge { padding: 6px 14px; border-radius: 10px; font-size: 0.7rem; font-weight: 800; display: inline-block; letter-spacing: 0.5px; }
        .bg-pending { background: #fff7ed; color: #c2410c; }
        .bg-borrowed { background: #eef2ff; color: #4338ca; }
        .bg-returned { background: #f0fdf4; color: #15803d; }
        /* Badge Status Ditolak */
        .bg-rejected { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }

        .btn-action { border-radius: 12px; font-weight: 700; font-size: 0.75rem; padding: 8px 16px; transition: 0.3s; text-decoration: none !important; }
        .denda-tag { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; padding: 4px 10px; border-radius: 8px; font-size: 0.65rem; font-weight: 700; display: inline-flex; align-items: center; }

        @media (max-width: 992px) { 
            .sidebar { left: -260px; } 
            .sidebar.active { left: 0; }
            .content-wrapper { margin-left: 0; padding: 1.5rem; padding-top: 5rem; } 
            #menuToggle { position: fixed; top: 1rem; left: 1rem; z-index: 100000; }
        }
    </style>
</head>
<body>

<?php if(isset($_SESSION['flash'])) : ?>
    <script>
        Swal.fire({ title: '<?= $_SESSION['flash']['pesan']; ?>', text: '<?= $_SESSION['flash']['aksi']; ?>', icon: '<?= $_SESSION['flash']['tipe']; ?>', timer: 2500, showConfirmButton: false });
    </script>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<button class="btn btn-white shadow-sm border d-lg-none" id="menuToggle">
    <i class="fas fa-bars text-primary"></i> Menu
</button>

<div class="sidebar shadow" id="mainSidebar">
    <div class="sidebar-brand">
        SIPEMINJAM<br>
        <span class="role-badge"><?= strtoupper($_SESSION['role'] ?? 'USER'); ?></span>
    </div>
    <nav class="nav flex-column mt-4">
        <a class="nav-link" href="<?= BASEURL; ?>/AdminController/index"><i class="fas fa-th-large me-2"></i> Dashboard</a>
        
        <?php if(in_array(strtolower($_SESSION['role'] ?? ''), ['admin', 'petugas'])) : ?>
            <a class="nav-link" href="<?= BASEURL; ?>/AdminController/alat"><i class="fas fa-toolbox me-2"></i> Daftar Alat</a>
            <a class="nav-link" href="<?= BASEURL; ?>/AdminController/laporan"><i class="fas fa-file-alt me-2"></i> Laporan</a>
        <?php endif; ?>
        
        <a class="nav-link active" href="<?= BASEURL; ?>/AdminController/transaksi">
            <i class="fas fa-exchange-alt me-2"></i> <?= (strtolower($_SESSION['role'] ?? '') === 'user') ? 'Riwayat Pinjam' : 'Monitoring'; ?>
        </a>

        <div class="mt-4 pt-3 border-top border-secondary">
            <a class="nav-link text-danger" href="<?= BASEURL; ?>/AuthController/logout"><i class="fas fa-power-off me-2"></i> Keluar</a>
        </div>
    </nav>
</div>

<div class="content-wrapper">
    <div class="header-section d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 2px;">Inventaris Log</span>
            <h2 class="fw-bold"><?= (strtolower($_SESSION['role'] ?? '') === 'user') ? 'Status Pinjaman Saya' : 'Monitoring Transaksi'; ?></h2>
        </div>
        
        <?php if(in_array(strtolower($_SESSION['role'] ?? ''), ['admin', 'petugas'])) : ?>
            <a href="<?= BASEURL; ?>/AdminController/laporan" target="_blank" class="btn btn-primary btn-action shadow-sm">
                <i class="fas fa-print me-2"></i> CETAK LAPORAN
            </a>
        <?php endif; ?>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>PEMINJAM</th>
                        <th>ALAT</th>
                        <th>TANGGAL PINJAM</th>
                        <th>TGL KEMBALI</th>
                        <th class="text-center">STATUS & DENDA</th>
                        <th class="text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['transaksi'])) : foreach($data['transaksi'] as $t) : ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:32px; height:32px; font-size:0.75rem;">
                                    <?= strtoupper(substr($t['nama_lengkap'] ?? $t['username'] ?? 'U', 0, 1)); ?>
                                </div>
                                <span class="fw-600"><?= $t['nama_lengkap'] ?? $t['username'] ?? 'User'; ?></span>
                            </div>
                        </td>
                        <td class="fw-600"><?= $t['nama_alat']; ?></td>
                        <td class="text-muted small"><?= date('d/m/Y', strtotime($t['tgl_pinjam'])); ?></td>
                        
                        <td class="small">
                            <?php 
                                $status_transaksi = strtolower(trim($t['status'] ?? ''));
                                $tgl_db = $t['tgl_kembali'] ?? '';
                                
                                if ($status_transaksi === 'kembali') : ?>
                                    <span class="text-success fw-bold">
                                        <i class="fas fa-check-circle me-1"></i>
                                        <?php 
                                            if (!empty($tgl_db) && $tgl_db != '0000-00-00 00:00:00') {
                                                echo date('d/m/Y', strtotime($tgl_db));
                                            } else {
                                                echo date('d/m/Y'); 
                                            }
                                        ?>
                                    </span>
                                <?php elseif ($status_transaksi === 'ditolak') : ?>
                                    <span class="text-danger small">Dibatalkan</span>
                                <?php else : ?>
                                    <span class="text-warning fw-bold">
                                        <i class="fas fa-clock me-1"></i> Belum Kembali
                                    </span>
                                <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <?php 
                                $st = strtolower(trim($t['status'] ?? 'pending')); 
                                // Kondisi warna badge diperluas untuk status ditolak
                                $cls = 'bg-pending';
                                if($st == 'dipinjam') $cls = 'bg-borrowed';
                                elseif($st == 'kembali') $cls = 'bg-returned';
                                elseif($st == 'ditolak') $cls = 'bg-rejected';
                            ?>
                            <div class="d-flex flex-column align-items-center gap-1">
                                <span class="status-badge <?= $cls; ?>"><?= strtoupper($st); ?></span>
                                <?php if(isset($t['denda']) && $t['denda'] > 0) : ?>
                                    <div class="denda-tag">
                                        <i class="fas fa-wallet me-1"></i> Rp <?= number_format($t['denda'], 0, ',', '.'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="text-end">
                            <?php 
                                $status_clean = strtolower(trim($t['status'] ?? '')); 
                                $role_user = strtolower($_SESSION['role'] ?? '');
                                $current_user_id = $_SESSION['id_user'] ?? 0;
                                $owner_id = $t['id_user'] ?? -1;
                            ?>

                            <?php if($status_clean == 'pending') : ?>
                                <?php if(in_array($role_user, ['admin', 'petugas'])) : ?>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="<?= BASEURL; ?>/AdminController/konfirmasi/<?= $t['id']; ?>" class="btn btn-success btn-action btn-confirm">SETUJUI</a>
                                        <a href="<?= BASEURL; ?>/AdminController/tolak/<?= $t['id']; ?>" class="btn btn-danger btn-action btn-reject">TOLAK</a>
                                    </div>
                                <?php else : ?>
                                    <span class="badge bg-light text-muted fw-normal border">Menunggu...</span>
                                <?php endif; ?>

                            <?php elseif($status_clean == 'dipinjam') : ?>
                                <?php if($current_user_id == $owner_id) : ?>
                                    <a href="<?= BASEURL; ?>/AdminController/kembalikan/<?= $t['id']; ?>" class="btn btn-info text-white btn-action btn-return">
                                        <i class="fas fa-undo me-1"></i> KEMBALIKAN
                                    </a>
                                <?php else : ?>
                                    
                                <span class="badge fw-bold" style="font-size: 0.65rem; background-color: #0a1538; color: #ffffff; padding: 8px 15px; border-radius: 50px; border: 1px solid #c7d2fe;"> SEDANG DIPAKAI
                                </span>
                                <?php endif; ?>
                            
                            <?php elseif($status_clean == 'ditolak') : ?>
                                <i class="fas fa-times-circle text-danger fs-5 opacity-50"></i>

                            <?php else : ?>
                                <i class="fas fa-check-circle text-success fs-5"></i>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else : ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data transaksi.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function() {
    $('#menuToggle').on('click', function(e) {
        e.stopPropagation();
        $('#mainSidebar').toggleClass('active');
    });
    $('.nav-link').on('click', function() {
        const dest = $(this).attr('href');
        if (dest && dest !== '#') window.location.href = dest;
    });
    
    // Logic SweetAlert untuk Confirm, Return, dan Reject (Tolak)
    $('.btn-confirm, .btn-return, .btn-reject').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const isReturn = $(this).hasClass('btn-return');
        const isReject = $(this).hasClass('btn-reject');
        
        let title = 'Konfirmasi Pinjaman?';
        let text = 'User akan diizinkan meminjam alat.';
        let icon = 'question';
        let confirmBtnColor = '#6366f1';

        if(isReturn) {
            title = 'Kembalikan Alat?';
            text = 'Alat akan ditandai telah kembali.';
        } else if(isReject) {
            title = 'Tolak Pinjaman?';
            text = 'Permintaan peminjaman ini akan dibatalkan.';
            icon = 'warning';
            confirmBtnColor = '#ef4444';
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmBtnColor,
            confirmButtonText: 'Ya, Proses!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = url;
        });
    });
});
</script>
</body>
</html>