<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alat - Sistem Peminjaman</title>
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/bootstrap.css">
    <style>
        body { background: #f5f5f5; }
        .navbar-brand { font-weight: 700; letter-spacing: .03em; }
        .hero { padding: 2.5rem 0 1.5rem; }
        .hero-title { font-weight: 700; }
        .table-card { border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .badge-kategori { font-size: .7rem; text-transform: uppercase; letter-spacing: .04em; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand text-primary" href="#">
            SIPEMINJAM
        </a>
        <div class="ms-auto">
            <a href="<?= BASEURL; ?>/auth/logout" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<main class="container">
    <section class="hero text-center text-md-start">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h1 class="hero-title mb-2">Daftar Alat Tersedia</h1>
                <p class="text-muted mb-0">
                    Pilih alat yang ingin Anda pinjam, lalu konfirmasi ke petugas.
                </p>
            </div>
        </div>
    </section>

    <section>
        <div class="card table-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Data Alat</h5>
                    <span class="text-muted small">
                        Total: <?= isset($data['alat']) ? count($data['alat']) : 0; ?> alat
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Alat</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Ketersediaan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($data['alat'])): ?>
                            <?php $no = 1; foreach ($data['alat'] as $alat): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($alat['nama_alat']); ?></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary badge-kategori">
                                            <?= htmlspecialchars($alat['nama_kategori']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success">Tersedia</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada data alat yang tersedia.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

</body>
</html>

