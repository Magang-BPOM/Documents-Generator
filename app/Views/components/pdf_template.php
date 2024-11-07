<?php
function formatList($text)
{

    $text = str_replace(";", "\n", $text);


    $items = explode("\n", $text);

    $items = array_map(function ($item) {
        return trim($item);
    }, $items);

    $items = array_filter($items, function ($item) {
        return !empty($item);
    });


    $formatted = [];
    foreach ($items as $item) {
        $formatted[] = $item;
    }

    for ($i = 0; $i < count($formatted); $i++) {
        $formatted[$i] = ($i + 1) . ". " . $formatted[$i];
    }

    return $formatted;
}

$dasarArray = formatList($surat['dasar']);
$untukArray = formatList($surat['untuk']);

function formatTanggalIndonesia($tanggal)
{
    $fmt = new IntlDateFormatter(
        'id_ID',
        IntlDateFormatter::LONG, // Format tanggal panjang (24 Oktober 2024)
        IntlDateFormatter::NONE, // Tidak menggunakan format waktu
        'Asia/Jakarta', // Timezone
        IntlDateFormatter::GREGORIAN // Kalender Gregorian
    );
    return $fmt->format(new DateTime($tanggal));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas</title>
    <style>
        * {
            max-width: 100vw;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 16px;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        header {
            text-align: center;
            margin: 20px;
        }

        .content {
            padding: 0 80px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .content p,
        .content ul {
            margin-bottom: 10px;
            text-align: justify;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-height: 50%;
            text-align: center;

        }

        .label-content {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .label-content p:first-child {
            display: table-cell;
            width: 150px;
            vertical-align: top;
            padding-right: 5px;
        }

        .label-content p:nth-child(2),
        .label-content ul {
            display: table-cell;
            padding-left: 5px;
        }

        .label-content ul {
            list-style-type: none;
        }

        .colon {
            display: table-cell;
            width: 30px;
            text-align: center;
            vertical-align: top;
        }

        .user-info li {
            margin-bottom: 10px;
        }

        .page-break {
            page-break-before: always;
        }
        .wrap {
            margin: 80px;
            font-size: 18px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 36px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
            font-size : 18px
        }

        .table th {
            background-color: #f2f2f2;
        }
        .section-lampiran p {
    margin-left: calc(58% - 100px);
    width: 100%; 
    text-align: left;
}

    </style>
</head>

<body>
    <!-- Halaman pertama -->
    <header>
        <img src="<?= esc($header_image) ?>" alt="Header" style="width: 100%; max-height: 100%; object-fit: fill;">
    </header>

    <div class="content">
        <div class="section-title">
            <p style="text-align: center;">SURAT TUGAS</p>
            <p style="text-align: center;">NOMOR: <?= esc($surat['nomor_surat']) ?></p>
        </div>

        <div class="label-content">
            <p>Menimbang</p>
            <span class="colon">:</span>
            <p><?= esc($surat['menimbang']) ?></p>
        </div>

        <div class="label-content">
            <p>Dasar</p>
            <span class="colon">:</span>
            <ul>
                <?php foreach ($dasarArray as $item): ?>
                    <li><?= esc($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="label-content">
            <p style="text-align:center">Memberi Tugas</p>
        </div>

        <div class="label-content">
            <p>Kepada</p>
            <span class="colon">:</span>
            <?php if (count($users) > 2): ?>
                <p>Nama-nama terlampir</p>
            <?php else: ?>
                <ul class="user-info">
                    <?php foreach ($users as $user): ?>
                        <li>
                            Nama: <?= esc($user['nama']) ?><br>
                            NIP: <?= esc($user['nip']) ?><br>
                            Pangkat/Gol: <?= esc($user['pangkat']) ?><br>
                            Jabatan: <?= esc($user['jabatan']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="label-content">
            <p>Untuk</p>
            <span class="colon">:</span>
            <ul>
                <?php foreach ($untukArray as $item): ?>
                    <li><?= esc($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <p>Agar yang bersangkutan melaksanakan tugas dengan baik dan penuh tanggung jawab.</p>
        <div class="signature" style="margin-right: 60px;margin-top:20px;">
            <p style="text-align:right;"><?= formatTanggalIndonesia($surat['ttd_tanggal']) ?>,</p>
            <p style="text-align:right;"><?= esc($surat['jabatan_ttd']) ?>,</p>
            <br><br><br><br>
            <p style="text-align:right"><?= esc($surat['penanda_tangan']) ?></p>
        </div>
    </div>

    <footer>
        <img src="<?= esc($footer_image) ?>" alt="Footer">
    </footer>

    <!-- Halaman kedua untuk lampiran -->
    <?php if (count($users) > 2): ?>
        <div class="page-break">
    <div class="wrap">
        <p class="text-md" style="text-align: center; font-size: 18px; margin-bottom: 8px;">LAMPIRAN</p>
        <div class="section-lampiran mt-6" style="text-align: center;">
            <p class="text-md" style="text-align: left;margin-bottom: 8px;">SURAT TUGAS KEPALA BBPOM DI SURABAYA</p>
            <p class="text-md" style="text-align: left;margin-bottom: 8px;">NOMOR: <?= esc($surat['nomor_surat']) ?></p>
            <p class="text-md" style="text-align: left;">TANGGAL: <?= formatTanggalIndonesia($surat['ttd_tanggal']) ?></p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Pangkat/Gol</th>
                    <th>Jabatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($user['nama']) ?></td>
                        <td><?= esc($user['nip']) ?></td>
                        <td><?= esc($user['pangkat']) ?></td>
                        <td><?= esc($user['jabatan']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="end"  style="border:1px solid; padding:8px;margin-top : 300px">
        <p>Petugas tidak diperkenankan menerima gratifikasi dalam bentuk apapun.</p>
        </div>
           
    </div>
</div>



    <?php endif; ?>
</body>

</html>