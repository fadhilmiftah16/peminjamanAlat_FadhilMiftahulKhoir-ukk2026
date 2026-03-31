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
        :root { --primary: #6366f1; --bg-body: #f8fafc; --sidebar: #0f172a; }
        body { background-color: var(--bg-body); font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; }
        .sidebar { height: 100vh; background-color: var(--sidebar); position: fixed; width: 260px; z-index: 1000; left: 0; top: 0; padding: 1.5rem; }
        .sidebar-brand { color: #fff; font-size: 1.4rem; font-weight: 800; display: block; text-decoration: none; text-align: center; margin-bottom: 2rem; }
        .nav-link { color: #94a3b8 !important; padding: 0.8rem 1rem !important; border-radius: 12px; display: flex; align-items: center; text-decoration: none; margin-bottom: 5px; }
        .nav-link.active { background: rgba(255,255,255,0.1) !important; color: #fff !important; }
        .content-wrapper { margin-left: 260px; padding: 2.5rem; }
        .tool-card { border: none; border-radius: 24px; background: #fff; border: 1px solid rgba(0,0,0,0.05); }
        .card-icon { height: 120px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--primary); border-radius: 20px; }
        .btn-premium { border-radius: 14px; padding: 10px 20px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="#" class="sidebar-brand">SIPEMINJAM<span class="text-primary">.</span></a>
    <nav class="nav flex-column">
        <a class="nav-link" href="<?= BASEURL; ?>/AdminController/index"><i class="fas fa-th-large me-2"></i> Dashboard</a>
        <a class="nav-link active" href="<?= BASEURL; ?>/AdminController/alat"><i class="fas fa-toolbox me-2"></i> Katalog Alat</a>
        <a class="nav-link" href="<?= BASEURL; ?>/AdminController/transaksi"><i class="fas fa-exchange-alt me-2"></i> Monitoring</a>
        <a class="nav-link text-danger mt-4" href="<?= BASEURL; ?>/AuthController/logout"><i class="fas fa-power-off me-2"></i> Keluar</a>
    </nav>
</div>

<div class="content-wrapper">
    <div class="header-section d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-primary fw-bold small text-uppercase" style="letter-spacing: 2px;">Inventaris Sistem</span>
            <h2 class="fw-bold">Katalog Alat</h2>
        </div>
        
        <?php if(isset($_SESSION['role']) && (strtolower($_SESSION['role']) == 'admin' || strtolower($_SESSION['role']) == 'petugas')) : ?>
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
                        <i class="fas <?= (strtolower($row['kategori']) == 'elektronik') ? 'fa-laptop' : 'fa-screwdriver-wrench'; ?>"></i>
                    </div>
                    <div class="card-body p-0">
                        <p class="text-muted small mb-1 fw-bold"><?= $row['kategori']; ?> | <?= $row['stok']; ?> Tersedia</p>
                        <h5 class="fw-bold mb-3"><?= $row['nama_alat']; ?></h5>
                        
                        <div class="d-flex gap-2">
                            <?php if(isset($_SESSION['role']) && (strtolower($_SESSION['role']) == 'admin' || strtolower($_SESSION['role']) == 'petugas')) : ?>
                                <button class="btn btn-warning btn-premium flex-grow-1 text-white tampilModalUbah" 
                                        data-bs-toggle="modal" data-bs-target="#formModal" 
                                        data-id="<?= $row['id']; ?>">Edit</button>
                                <a href="<?= BASEURL; ?>/AdminController/hapus/<?= $row['id']; ?>" class="btn btn-light btn-premium" onclick="return confirm('Hapus?')"><i class="fas fa-trash text-danger"></i></a>
                            <?php else : ?>
                                <button class="btn btn-primary btn-premium w-100 <?= ($row['stok'] <= 0) ? 'disabled' : ''; ?>" 
                                        data-bs-toggle="modal" data-bs-target="#modalPinjam" 
                                        onclick="persiapanPinjam('<?= $row['id']; ?>', '<?= $row['nama_alat']; ?>')">
                                    Pinjam
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="formModalLabel">Data Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASEURL; ?>/AdminController/tambah" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id">
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
                        <label class="form-label small fw-bold">Stok</label>
                        <input type="number" class="form-control shadow-none" name="stok" id="stok" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Foto Alat</label>
                        <input type="file" class="form-control shadow-none" name="gambar">
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="submit" class="btn btn-primary btn-premium w-100">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function() {
    $('.tombolTambahData').on('click', function() {
        $('#formModalLabel').html('Tambah Alat Baru');
        $('.modal-content form').attr('action', '<?= BASEURL; ?>/AdminController/tambah');
        $('#id').val('');
        $('#nama_alat').val('');
        $('#stok').val('');
    });
    $('.tampilModalUbah').on('click', function() {
        $('#formModalLabel').html('Ubah Data Alat');
        $('.modal-content form').attr('action', '<?= BASEURL; ?>/AdminController/ubah');
        const id = $(this).data('id');
        $.ajax({
            url: '<?= BASEURL; ?>/AdminController/getubah',
            data: {id : id},
            method: 'post',
            dataType: 'json',
            success: function(data) {
                $('#nama_alat').val(data.nama_alat);
                $('#kategori').val(data.kategori);
                $('#stok').val(data.stok);
                $('#id').val(data.id);
            }
        });
    });
});
function persiapanPinjam(id, nama) {
    Swal.fire({
        title: 'Pinjam Alat?',
        text: "Anda akan mengajukan peminjaman " + nama,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6366f1',
        confirmButtonText: 'Ya, Pinjam!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= BASEURL; ?>/AdminController/ajukanPinjam/" + id;
        }
    })
}
</script>
</body>
</html>