<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root { 
            --primary: #6366f1; 
            --bg-body: #f8fafc; 
            --sidebar: #0f172a; 
        }

        body { 
            background-color: var(--bg-body); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #1e293b; 
            overflow-x: hidden;
        }

        /* --- SIDEBAR FIX --- */
        .sidebar { 
            height: 100vh !important; 
            background-color: var(--sidebar) !important; 
            position: fixed !important; 
            width: 260px; 
            z-index: 1040 !important; /* Di bawah Modal tapi di atas konten */
            left: 0; 
            top: 0; 
            padding: 1.5rem; 
            transition: all 0.3s ease;
            pointer-events: auto !important;
        }

        .sidebar-brand { 
            color: #fff; 
            font-size: 1.4rem; 
            font-weight: 800; 
            display: block; 
            text-decoration: none; 
            text-align: center; 
            margin-bottom: 2rem; 
        }

        .nav-link { 
            color: #94a3b8 !important; 
            padding: 0.8rem 1rem !important; 
            border-radius: 12px; 
            display: flex; 
            align-items: center; 
            text-decoration: none !important; 
            margin-bottom: 5px; 
            transition: 0.3s;
        }

        .nav-link:hover, .nav-link.active { 
            background: rgba(255,255,255,0.1) !important; 
            color: #fff !important; 
        }

        .nav-link.active {
            background: var(--primary) !important;
        }

        .content-wrapper { 
            margin-left: 260px; 
            padding: 2.5rem; 
            position: relative;
            z-index: 1;
            transition: 0.3s;
        }

        /* --- MODAL FIX (PENTING) --- */
        .modal { z-index: 1060 !important; }
        .modal-backdrop { z-index: 1050 !important; }

        .tool-card { border: none; border-radius: 24px; background: #fff; border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .tool-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .card-icon { height: 180px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 3.5rem; color: var(--primary); border-radius: 20px; overflow: hidden; }
        .card-icon img { width: 100%; height: 100%; object-fit: cover; }
        .btn-premium { border-radius: 14px; padding: 10px 20px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; }

        @media (max-width: 992px) { 
            .sidebar { left: -260px !important; } 
            .sidebar.active { left: 0 !important; z-index: 1070 !important; }
            .content-wrapper { margin-left: 0 !important; padding: 1.5rem; padding-top: 5rem; } 
            #btnToggleMobile { position: fixed; top: 15px; left: 15px; z-index: 1000; }
        }
    </style>
</head>
<body>

<div class="sidebar shadow" id="mainSidebar">
    <a href="<?= BASEURL; ?>/AdminController/index" class="sidebar-brand">SIPEMINJAM<span class="text-primary">.</span></a>
    <nav class="nav flex-column">
        <a class="nav-link" href="<?= BASEURL; ?>/AdminController/index">
            <i class="fas fa-th-large me-2"></i> Dashboard
        </a>
        <a class="nav-link active" href="<?= BASEURL; ?>/AdminController/alat">
            <i class="fas fa-toolbox me-2"></i> Katalog Alat
        </a>
        <a class="nav-link" href="<?= BASEURL; ?>/AdminController/transaksi">
            <i class="fas fa-exchange-alt me-2"></i> Monitoring
        </a>
        <div class="mt-4 pt-3 border-top border-secondary">
            <a class="nav-link text-danger" href="<?= BASEURL; ?>/AuthController/logout">
                <i class="fas fa-power-off me-2"></i> Keluar
            </a>
        </div>
    </nav>
</div>

<div class="content-wrapper">
    <button class="btn btn-white shadow-sm d-lg-none mb-3 border" id="btnToggleMobile">
        <i class="fas fa-bars text-primary"></i> Menu
    </button>

    <div class="header-section d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-primary fw-bold small text-uppercase" style="letter-spacing: 2px;">Inventaris Sistem</span>
            <h2 class="fw-bold">Katalog Alat</h2>
        </div>
        
        <?php if(isset($_SESSION['role']) && (in_array(strtolower($_SESSION['role']), ['admin', 'petugas']))) : ?>
            <button class="btn btn-dark btn-premium shadow-sm tombolTambahData" data-bs-toggle="modal" data-bs-target="#formModal">
                <i class="fas fa-plus me-2"></i> Tambah Data
            </button>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php if(!empty($data['alat'])) : foreach ($data['alat'] as $row) : ?>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card tool-card h-100 p-3">
                    <div class="card-icon mb-3">
                        <?php if(!empty($row['gambar']) && $row['gambar'] != 'NULL') : ?>
                            <img src="<?= BASEURL; ?>/assets/img/<?= $row['gambar']; ?>" alt="<?= $row['nama_alat']; ?>" onerror="this.src='https://placehold.co/400x300?text=No+Image';">
                        <?php else : ?>
                            <i class="fas <?= (strtolower($row['kategori'] ?? '') == 'elektronik') ? 'fa-laptop' : 'fa-screwdriver-wrench'; ?>"></i>
                        <?php endif; ?>
                    </div>

                    <div class="card-body p-0">
                        <p class="text-muted small mb-1 fw-bold"><?= strtoupper($row['kategori'] ?? 'UMUM'); ?> | <?= $row['stok']; ?> Tersedia</p>
                        <h5 class="fw-bold mb-3"><?= $row['nama_alat']; ?></h5>
                        
                        <div class="d-flex gap-2">
                            <?php if(in_array(strtolower($_SESSION['role'] ?? ''), ['admin', 'petugas'])) : ?>
                                <button class="btn btn-warning btn-premium flex-grow-1 text-white tampilModalUbah" 
                                        data-bs-toggle="modal" data-bs-target="#formModal" 
                                        data-id="<?= $row['id']; ?>">Edit</button>
                                <a href="<?= BASEURL; ?>/AdminController/hapus/<?= $row['id']; ?>" class="btn btn-light btn-premium border btn-hapus"><i class="fas fa-trash text-danger"></i></a>
                            <?php else : ?>
                                <button class="btn btn-primary btn-premium w-100 <?= ($row['stok'] <= 0) ? 'disabled' : ''; ?>"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalPinjam<?= $row['id']; ?>">
                                    <?= ($row['stok'] <= 0) ? 'Stok Habis' : 'Pinjam Sekarang'; ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; else : ?>
            <div class="col-12 text-center py-5"><p class="text-muted">Oops! Katalog masih kosong.</p></div>
        <?php endif; ?>
    </div>
</div>

<?php if(!empty($data['alat'])) : foreach ($data['alat'] as $row) : ?>
    <div class="modal fade" id="modalPinjam<?= $row['id']; ?>" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 25px; background: #fff;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Ajukan Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= BASEURL; ?>/AdminController/ajukanPinjam" method="post">
                    <input type="hidden" name="id_alat" value="<?= $row['id']; ?>">
                    <div class="modal-body p-4">
                        <div class="text-center mb-3">
                            <h4 class="fw-bold text-primary"><?= $row['nama_alat']; ?></h4>
                            <p class="text-muted small">Maksimal pinjam: <?= $row['stok']; ?> unit</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Jumlah Pinjam</label>
                            <input type="number" class="form-control shadow-none" name="jumlah_pinjam" min="1" max="<?= $row['stok']; ?>" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tanggal Pinjam</label>
                            <input type="date" class="form-control shadow-none" name="tgl_pinjam" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="submit" class="btn btn-primary btn-premium w-100 shadow">KONFIRMASI PINJAM</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; endif; ?>

<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="formModalLabel">Data Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASEURL; ?>/AdminController/tambah" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="gambar_lama" id="gambar_lama">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Alat</label>
                        <input type="text" class="form-control shadow-none" name="nama_alat" id="nama_alat" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kategori</label>
                        <select class="form-select shadow-none" name="kategori" id="kategori">
                            <option value="Elektronik">Elektronik</option>
                            <option value="Perkakas">Perkakas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Jumlah Stok</label>
                        <input type="number" class="form-control shadow-none" name="stok" id="stok" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Upload Foto Baru (Opsional)</label>
                        <input type="file" class="form-control shadow-none" name="gambar">
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="submit" class="btn btn-primary btn-premium w-100 shadow">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function() {
    // Alert Flash Session
    <?php if(isset($_SESSION['flash'])) : ?>
        Swal.fire({
            title: '<?= $_SESSION['flash']['pesan']; ?>',
            text: '<?= $_SESSION['flash']['aksi']; ?>',
            icon: '<?= $_SESSION['flash']['tipe']; ?>'
        });
    <?php unset($_SESSION['flash']); endif; ?>

    // Toggle Sidebar Mobile
    $('#btnToggleMobile').on('click', function(e) {
        e.stopPropagation();
        $('#mainSidebar').toggleClass('active');
    });

    // Close sidebar when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#mainSidebar').length && $('#mainSidebar').hasClass('active')) {
            $('#mainSidebar').removeClass('active');
        }
    });

    // Hapus Data
    $('.btn-hapus').on('click', function(e) {
        e.preventDefault();
        const link = $(this).attr('href');
        Swal.fire({
            title: 'Hapus data?',
            text: "Data akan hilang permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) { window.location.href = link; }
        });
    });

    // Modal Admin (Tambah/Ubah)
    $('.tombolTambahData').on('click', function() {
        $('#formModalLabel').html('Tambah Alat Baru');
        $('.modal-content form').attr('action', '<?= BASEURL; ?>/AdminController/tambah');
        $('#id, #nama_alat, #stok, #gambar_lama').val('');
    });

    $('.tampilModalUbah').on('click', function() {
        $('#formModalLabel').html('Ubah Data Alat');
        $('.modal-content form').attr('action', '<?= BASEURL; ?>/AdminController/ubah');
        const id = $(this).data('id');
        $.ajax({
            url: '<?= BASEURL; ?>/AdminController/getubah/' + id,
            method: 'get',
            dataType: 'json',
            success: function(data) {
                $('#nama_alat').val(data.nama_alat);
                $('#kategori').val(data.kategori);
                $('#stok').val(data.stok);
                $('#id').val(data.id);
                $('#gambar_lama').val(data.gambar);
            }
        });
    });
});
</script>
</body>
</html>