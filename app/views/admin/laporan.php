<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - SIPEMINJAM</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #000; padding-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { border: 1px solid #444; padding: 12px 8px; text-align: center; font-size: 13px; }
        th { background-color: #f2f2f2; text-transform: uppercase; font-weight: bold; }
        
        /* Warna Status */
        .status-text { font-weight: bold; }
        .text-kembali { color: #166534; }
        .text-pinjam { color: #1e40af; }
        .text-pending { color: #92400e; }
        .text-ditolak { color: #b91c1c; }

        .footer-sign { margin-top: 50px; float: right; width: 250px; text-align: center; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            table { border: 1px solid #000; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #10b981; color: white; border: none; border-radius: 5px; font-weight: bold;">
            🖨️ Download / Cetak Laporan
        </button>
        <a href="<?= BASEURL; ?>/AdminController/transaksi" style="text-decoration: none; color: #666; margin-left: 15px; font-size: 14px;">← Kembali</a>
    </div>

    <div class="header">
        <h1 style="margin: 0; font-size: 24px;">LAPORAN PEMINJAMAN ALAT</h1>
        <p style="margin: 5px 0; font-size: 16px;">Sistem Informasi Inventaris SIPEMINJAM</p>
        <p style="margin: 0; font-size: 12px; color: #666;">Dicetak pada: <?= date('d F Y H:i'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Peminjam</th>
                <th style="width: 25%;">Nama Alat</th>
                <th style="width: 15%;">Tgl Pinjam</th>
                <th style="width: 15%;">Tgl Kembali</th>
                <th style="width: 20%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($data['transaksi'] as $t) : ?>
            <tr>
                <td><?= $i++; ?></td>
                <td style="text-align: left; padding-left: 15px;">
                    <?= $t['nama_lengkap'] ?? $t['username'] ?? 'User'; ?>
                </td>
                <td style="text-align: left; padding-left: 15px;"><?= $t['nama_alat']; ?></td>
                <td><?= date('d/m/Y', strtotime($t['tgl_pinjam'])); ?></td>
                <td>
                    <?php 
                        // Cek kemungkinan nama kolom tgl_kembali atau tanggal_kembali
                        $tgl_k = $t['tanggal_kembali'] ?? $t['tgl_kembali'] ?? null;
                        $status_cek = strtolower($t['status'] ?? '');

                        if ($status_cek === 'kembali' && !empty($tgl_k) && $tgl_k != '0000-00-00 00:00:00') : 
                    ?>
                        <strong><?= date('d/m/Y', strtotime($tgl_k)); ?></strong>
                    <?php elseif ($status_cek === 'ditolak') : ?>
                        <span style="color: #b91c1c;">-</span>
                    <?php else : ?>
                        <em style="color: #999;">Belum Kembali</em>
                    <?php endif; ?>
                </td>
                <td>
                    <?php 
                        $status = strtolower($t['status'] ?? 'pending');
                        $class = "text-pending";
                        if($status == 'kembali') $class = "text-kembali";
                        if($status == 'dipinjam') $class = "text-pinjam";
                        if($status == 'ditolak') $class = "text-ditolak";
                    ?>
                    <span class="status-text <?= $class; ?>">
                        <?= strtoupper($status); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer-sign">
        <p>Bandung, <?= date('d F Y'); ?></p>
        <p style="margin-bottom: 80px;">Petugas Admin,</p>
        <p><strong>( <?= $_SESSION['nama'] ?? $_SESSION['nama_lengkap'] ?? 'Administrator'; ?> )</strong></p>
        <hr style="border: 0.5px solid #000; width: 80%;">
    </div>
</body>
</html>