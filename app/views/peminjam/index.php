<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Alat</title>
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/bootstrap.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1">SIPEMINJAM</span>
            <a href="<?= BASEURL; ?>/auth/logout" class="btn btn-light btn-sm">Keluar</a>
        </div>
    </nav>

    <div class="container">
        <div class="card border-0 shadow-sm p-4">
            <h4 class="fw-bold mb-4">Pilih Alat yang Ingin Dipinjam</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($data['alat'] as $alt) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $alt['nama_alat']; ?></td>
                            <td><span class="badge bg-info text-dark"><?= $alt['nama_kategori']; ?></span></td>
                            <td><?= $alt['stok']; ?></td>
                            <td>
                                <a href="<?= BASEURL; ?>/peminjaman/pinjam/<?= $alt['id_alat']; ?>" class="btn btn-sm btn-success px-3">Pinjam</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>