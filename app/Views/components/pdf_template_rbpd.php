<?php

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
function terbilang($angka)
{
    $angka = abs($angka);
    $angkaTerbilang = [
        '',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan',
        'sepuluh',
        'sebelas'
    ];
    $hasil = '';

    if ($angka < 12) {
        $hasil = ' ' . $angkaTerbilang[$angka];
    } elseif ($angka < 20) {
        $hasil = terbilang($angka - 10) . ' belas';
    } elseif ($angka < 100) {
        $hasil = terbilang(floor($angka / 10)) . ' puluh' . terbilang($angka % 10);
    } elseif ($angka < 200) {
        $hasil = ' seratus ' . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        $hasil = terbilang(floor($angka / 100)) . ' ratus' . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        $hasil = ' seribu' . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $hasil = terbilang(floor($angka / 1000)) . ' ribu' . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        $hasil = terbilang(floor($angka / 1000000)) . ' juta ' . terbilang($angka % 1000000);
    } else {
        $hasil = 'Angka terlalu besar';
    }

    return trim($hasil);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincian Biaya Perjalanan Dinas</title>
    <style>
        @page {
            size: 210mm 330mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 16px;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            margin: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            padding: 0 15px;
        }

        .content-title {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .content-title p:first-child {
            display: table-cell;
            width: 150px;
            vertical-align: top;
            padding-right: 5px;
        }

        .content-title p:nth-child(2) {
            display: table-cell;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 10px;

        }

        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .table tfoot {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .table td.text-right {
            text-align: right !important;
        }

        .table td.text-left {
            text-align: left !important;
        }

        .table td.text-center {
            text-align: center !important;
        }


        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .section-title {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .signature-section {
            margin-top: 10px;
        }

        .divider {
            border-top: 2px solid black;
            /* margin-top: 1px; */
            margin-bottom: 10px;
            margin-left: 20px;
            margin-right: 20px;
        }

        .center {
            text-align: center;
            font-weight: bold;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .summary-table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .summary-table td {
            padding: 4px;
        }

        .summary-table td:nth-child(2) {
            text-align: center;
        }

        .summary-table td:nth-child(3) {
            text-align: right;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
        }

        .empty-row td {
            height: 15px;
        }

        .two-column-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .two-column-table td {
            vertical-align: top;
            width: 50%;
            padding: 10px;
        }

        .two-column-table .section {
            text-align: center;
        }

        .two-column-table .section p {
            margin-bottom: 10px;
        }

        .two-column-table .section .amount {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 5px;
            width: 100%;
        }

        .two-column-table .section .amount span:first-child {
            flex: 0 0 auto;
            text-align: left;
            width: 10%;
        }

        .two-column-table .section .amount span:last-child {
            flex: 0 0 auto;
            text-align: right;
            width: 70%;
        }

        .final-signature {
            width: 50%;
            margin-top: 10px;
            border-collapse: collapse;
            position: absolute;
            right: 3%;
            z-index: 10;
        }

        .final-signature td {
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }
    </style>
</head>

<body>
    <header>
        <img src="<?= esc($header_image) ?>" alt="Header" style="width: 100%; max-height: 100%; object-fit: fill;">
    </header>

    <div class="content">
        <div class="section-title">
            RINCIAN BIAYA PERJALANAN DINAS
        </div>


        <div class="content-title">
            <p>Lampiran SPD Nomor</p>
            <p class="colon">:</p>
            <p class="value"><?= esc($surat['nomor_surat']) ?></p>
        </div>
        <div class="content-title">
            <p>Tanggal</p>
            <p class="colon">:</p>
            <p class="value"><?= formatTGL($surat['created_at'], 'tanggal') ?></p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Perincian Biaya</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($rincian_biaya as $index => $biaya): ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td class="text-left"><?= esc($biaya['perincian_biaya']) ?></td>
                        <td class="text-center">Rp <?= number_format($biaya['jumlah'], 2, ',', '.') ?></td>
                        <td><?= esc($biaya['keterangan'] ?? '-') ?></td>
                    </tr>
                    <?php $total += $biaya['jumlah']; ?>
                <?php endforeach; ?>
                <tr class="empty-row">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><strong>Jumlah</strong></td>
                    <td class="text-center"><strong>Rp <?= number_format($total, 2, ',', '.') ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot class="bg-gray-500">
                <tr>
                    <td><strong>Terbilang:</strong></td>
                    <td colspan="3"><?= strtoupper(terbilang($total)) ?> RUPIAH</td>

                </tr>
            </tfoot>
        </table>

        <div class="signature-section">
            <p style="text-align:right;margin-right:92px">Surabaya, <?= formatTGL($surat['created_at'], 'tanggal') ?>,</p>
            <table class="two-column-table">
                <tr>
                    <td>
                        <div class="section">
                            <p>Telah dibayar sejumlah:</p>
                            <p class="amount"><span>Rp</span> <span><?= number_format($total, 2, ',', '.') ?></span></p>
                            <p>Bendahara</p>
                            <br><br> <br>
                            <p><?= esc($bendahara['nama']) ?></p>
                            <p>NIP: <?= esc($bendahara['nip']) ?></p>
                        </div>
                    </td>
                    <td>
                        <div class="section">
                            <p>Telah menerima jumlah uang sebesar:</p>
                            <p class="amount"><span>Rp</span> <span><?= number_format($total, 2, ',', '.') ?></span></p>
                            <p>Penerima</p>
                            <br><br> <br>
                            <p><?= esc($penerima['nama']) ?></p>
                            <p>NIP: <?= esc($penerima['nip']) ?></p>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="divider"></div>
            <div class="center">PERHITUNGAN SPD RAMPUNG</div>

            <table class="summary-table">
                <tr>
                    <td>Ditentukan sejumlah</td>
                    <td>:</td>
                    <td>Rp <?= number_format($total, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td>Yang telah dibayar semula</td>
                    <td>:</td>
                    <td>Rp <?= number_format($total, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td>Sisa kurang/lebih</td>
                    <td>:</td>
                    <td>Rp 0,00</td>
                </tr>
            </table>

            <table class="final-signature">
                <tr>
                    <td>
                        <p>Mengetahui/Menyetujui</p>
                        <p>AN KUASA PENGGUNA ANGGARAN BALAI BESAR POM DI SURABAYA</p>
                        <p>PEJABAT PEMBUAT KOMITMEN</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <br><br><br>
                        <p><strong><?= esc($penanda_tangan['nama']) ?></strong></p>
                        <p>NIP: <?= esc($penanda_tangan['nip']) ?></p>
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <footer>
        <img src="<?= esc($footer_image) ?>" alt="Footer">
    </footer>
</body>

</html>