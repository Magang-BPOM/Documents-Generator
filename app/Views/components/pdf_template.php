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

function formatTGL($tanggal, $format = 'tanggal')
{

    $dateType = ($format === 'hari') ? IntlDateFormatter::FULL : IntlDateFormatter::LONG;

    $fmt = new IntlDateFormatter(
        'id_ID',
        $dateType,
        IntlDateFormatter::NONE,
        'Asia/Jakarta',
        IntlDateFormatter::GREGORIAN
    );

    return $fmt->format(new DateTime($tanggal));
}

function formatTanggalRentang($mulai, $berakhir)
{
    $formatterHari = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, 'EEEE');
    $formatterBulan = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, 'MMMM');

    $hariMulai = ucfirst($formatterHari->format(strtotime($mulai)));
    $hariBerakhir = ucfirst($formatterHari->format(strtotime($berakhir)));

    $tanggalMulai = date('d', strtotime($mulai));
    $tanggalBerakhir = date('d', strtotime($berakhir));

    $bulanMulai = ucfirst($formatterBulan->format(strtotime($mulai)));
    $bulanBerakhir = ucfirst($formatterBulan->format(strtotime($berakhir)));

    $tahunMulai = date('Y', strtotime($mulai));
    $tahunBerakhir = date('Y', strtotime($berakhir));

    if ($bulanMulai === $bulanBerakhir && $tahunMulai === $tahunBerakhir) {
        return "{$hariMulai} - {$hariBerakhir}, {$tanggalMulai} - {$tanggalBerakhir} {$bulanMulai} {$tahunMulai}";
    }

    if ($tahunMulai === $tahunBerakhir) {
        return "{$hariMulai} - {$hariBerakhir}, {$tanggalMulai} {$bulanMulai} - {$tanggalBerakhir} {$bulanBerakhir} {$tahunMulai}";
    }

    return "{$hariMulai} - {$hariBerakhir}, {$tanggalMulai} {$bulanMulai} {$tahunMulai} - {$tanggalBerakhir} {$bulanBerakhir} {$tahunBerakhir}";
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas</title>
    <style>
        @page {
            size: 210mm 330mm;
        }

        * {
            min-width: 100vw;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 18px;
        }

        .numbered-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .numbered-list li {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            margin-left: -5px;
        }


        .numbered-list li::before {
            margin-right: 10px;
            text-align: right;
            min-width: 20px;
        }

        .numbered-list li .list-text {
            text-indent: 1px;
            padding-left: 20px;
            margin-top: -23px;
        }

        .list-number {
            min-width: 20px;
            text-align: right;
            margin-right: 10px;

        }

        .list-text {
            flex-grow: 1;
            text-indent: 0;
        }

        @font-face {
            font-family: 'Bookman Old Style';
            src: url('/assets/font/BOOKOS.ttf') format('truetype');
        }

        body {
            font-family: "Bookman Old Style";
            min-height: 100vh;
            min-width: 100vw;
        }

        header {
            text-align: center;
            margin: 20px;
        }

        .content {
            padding: 0 85px;
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
            min-width: 100vw;
            min-height: 100%;
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
            width: 20px;
            text-align: center;
            vertical-align: top;
        }

        .user-info {
            list-style: none;
            padding: 0;
            margin: 0;

        }

        .user-info li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .list-user {
            min-width: 20px;
            text-align: right;
            margin-left: -5px;
        }

        .list-details {
            flex-grow: 1;
            padding-left: 15px;
            margin-top: -23px;
        }

        .list-untuk {
            list-style-type: decimal;
            padding-left: 20px;
            margin: 0;
        }

        .list-untuk li {
            margin-bottom: 4px;
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
            font-size: 18px
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
            <?php if (!empty($dasar)): ?>
                <ul class="numbered-list">
                    <?php $no = 1; ?>
                    <?php foreach ($dasar as $list): ?>
                        <li>
                            <span class="list-number"><?= $no++ ?>.</span>
                            <div class="list-text">
                                <?= esc($list['undang']) ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Tidak ada dasar yang ditemukan.</p>
            <?php endif; ?>
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
                    <?php $no = 1; ?>
                    <?php foreach ($users as $user): ?>
                        <li>
                            <span class="list-user"><?= $no++ ?>.</span>
                            <div class="list-details">
                                Nama: <?= esc($user['nama']) ?><br>
                                NIP: <?= esc($user['nip']) ?><br>
                                Pangkat/Gol: <?= esc($user['pangkat']) ?><br>
                                Jabatan: <?= esc($user['jabatan']) ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>


        <div class="label-content">
            <p>Untuk</p>
            <span class="colon">:</span>
            <ol class="list-untuk">
                <li>
                    Sebagai: <?= esc($surat['sebagai']) ?>
                </li>
                <li>
                    Waktu: <?= formatTanggalRentang($surat['waktu_mulai'], $surat['waktu_berakhir']); ?>
                </li>
                <li>
                    Tujuan: <?= esc($surat['kota_tujuan']) ?>
                </li>
                <?php if (!empty($surat['biaya'])): ?>
                    <li>
                        Biaya: <?= esc($surat['biaya']) ?>
                    </li>
                <?php endif; ?>
            </ol>
        </div>

        
        <p>Agar yang bersangkutan melaksanakan tugas dengan baik dan penuh tanggung jawab.</p>
        <div class="signature" style="margin-right: 40px;margin-top:20px;">
            <p style="text-align:right;margin-right:60px">Surabaya, <?= formatTGL($surat['created_at'], 'tanggal') ?>,</p>
            <p style="text-align:right;"><?= esc($kepala_balai['jabatan']) ?>,</p>
            <br><br><br><br>
            <p style="text-align:right;margin-right:60px"><?= esc($kepala_balai['nama']) ?></p>
        </div>


    </div>

    <div style="position:absolute;top:1125px;margin-left:125px;justify-content:center;text-align:center;border:1px solid black;align-items:center;max-width:530px">
        <p style="font-size:18px;text-align:center;align-items:center;justify-content:center">Petugas tidak diperkenankan menerima gratifikasi dalam bentuk apapun.</p>
    </div>

    <footer>
        <img src="<?= esc($footer_image) ?>" alt="Footer">
    </footer>

    <?php if (count($users) > 2): ?>
        <div class="page-break">
            <div class="wrap">
                <p class="text-md" style="text-align: center; font-size: 18px; margin-bottom: 8px;">LAMPIRAN</p>
                <div class="section-lampiran mt-6" style="text-align: center;">
                    <p class="text-md" style="text-align: left;margin-bottom: 8px;">SURAT TUGAS KEPALA BBPOM DI SURABAYA</p>
                    <p class="text-md" style="text-align: left;margin-bottom: 8px;">NOMOR: <?= esc($surat['nomor_surat']) ?></p>
                    <p class="text-md" style="text-align: left;">TANGGAL: <?= formatTGL($surat['created_at']) ?></p>
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

                
                <div class="signature" style="margin-right: 40px;margin-top:20px;">
                    <p style="text-align:right;margin-right:10px">Surabaya, <?= formatTGL($surat['created_at'], 'tanggal') ?>,</p>
                    <p style="text-align:right;"><?= esc($kepala_balai['jabatan']) ?>,</p>
                    <br><br><br><br>
                    <p style="text-align:right;margin-right:10px"><?= esc($kepala_balai['nama']) ?></p>
                </div>

                <div class="end" style="border:1px solid; padding:8px;margin-top : 300px">
                    <p>Petugas tidak diperkenankan menerima gratifikasi dalam bentuk apapun.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>