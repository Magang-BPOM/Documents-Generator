<?php
if (!function_exists('tanggalIndonesia')) {
    function tanggalIndonesia($tanggal)
    {
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        $tanggal_parts = explode('-', $tanggal);
        $tahun = $tanggal_parts[0];
        $bulan_text = $bulan[(int)$tanggal_parts[1]];
        $hari = (int)$tanggal_parts[2];

        return "{$hari} {$bulan_text} {$tahun}";
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perjalanan Dinas</title>
    <style>
        @page {
            size: 330mm 210mm;
        }
 
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }

        .header-table {
        width: 100%;
        border-collapse: collapse;
        border:0 !important 
    }

    .address-cell {
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        width: 70% Imp !important; 
    }

    .info-cell {
        text-align: left;
        font-size: 12px;
        width: 30% !important; 
    }
        .container {
            display: table;
            width: 100%;
            table-layout: fixed;
            border-spacing: 12;
            margin-top: -12mm;
        }

        .left,
        .right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            box-sizing: border-box;
            border: 1px solid black;

        }

        .header {
            text-align: center;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            font-size: 10px;
            vertical-align: top;
        }

        .left td:first-child {
            width: 5%;
            text-align: center;
        }

        .left td:nth-child(2) {
            width: 25%;
        }

        .left td:nth-child(3) {
            width: 70%;
        }

        ul {
            margin: 0;
            padding-left: 20px;
        }

        p {
            margin: 0 0 5px;
            font-size: 8px;
        }

        .wrap {
            display: flex;
            justify-content: flex-end;
            padding: 0;
            margin-top: 15px;
            margin-bottom: 24px;
            width: 100%;
            box-sizing: border-box;
        }

        .left .sign {
            text-align: left;
            width: 40%;
            margin-left: auto;
        }

        .left .sign p.date {
            border-bottom: 1px solid black;
            margin-bottom: 10px;
            width: 100%;
            display: inline-block;/
        }


        .left .sign p.title {
            margin-bottom: 40px;
            /* Sesuaikan jarak */
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .no-border {
            border-right: 0 !important;
            border-left: 0 !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Kolom Kiri -->
        <div class="left">
        <table class="header-table">
        <tr>
            <td class="address-cell" style=" width: 70% !important;border:0 !important">
                <p>BALAI BESAR PENGAWAS OBAT DAN MAKANAN</p>
                <p>Jl. Karang Menjangan No.20</p>
                <p>SURABAYA</p>
            </td>
            <td class="info-cell" style=" width: 30% !important; border:0 !important">
                <p>Lembar Ke:</p>
                <p>Kode No.:</p>
                <p>Nomor: <?= esc($surat['nomor_surat']) ?></p>
            </td>
        </tr>
        </table>
            <p style="font-weight: bold;font-size:12px;text-align:center;text-decoration:underline;margin-bottom : 15px;margin-top:15px">SURAT PERJALANAN DINAS (SPD)</p>


            <table>
                <tr>
                    <td class="no-border" style="text-align: center;">1</td>
                    <td>Pejabat Pembuat Komitmen</td>
                    <td colspan="2"><?= esc($penanda_tangan['nama']) ?></td>
                </tr>
                <tr>
                    <td class="no-border" style="text-align: center;">2</td>

                    <td>Nama / NIP Pegawai yang melaksanakan perjalanan dinas</td>
                    <td colspan="2">
                         <p><?= esc($user['nama']) ?></p>
                         <p>NIP: <?= esc($user['nip']) ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="no-border" style="text-align: center;">3</td>

                    <td>
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>Pangkat dan Golongan</li>
                            <li>Jabatan / Instansi</li>
                            <li>Tingkat Biaya Perjalanan Dinas</li>
                        </ul>
                    </td>
                    <td colspan="2">
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li><?= esc($user['pangkat']) ?></li>
                            <li><?= esc($user['jabatan']) ?></li>
                            <li><?= esc($surat['kategori_biaya']) ?></li>
                        </ul>
                    </td>
                </tr>

                <tr>
                    <td class="no-border" style="text-align: center;">4</td>

                    <td>Maksud Perjalanan Dinas</td>
                    <td colspan="2"><?= esc($surat['sebagai']) ?></td>
                </tr>
                <tr>
                    <td class="no-border" style="text-align: center;">5</td>

                    <td>Alat Angkutan yang Dipergunakan</td>
                    <td colspan="2">Kendaraan Dinas</td>
                </tr>
                <tr>
                    <td class="no-border" style="text-align: center;">6</td>

                    <td>
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>Tempat Berangkat</li>
                            <li>Tujuan</li>
                        </ul>
                    </td>
                    <td colspan="2">
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>Surabaya</li>
                            <li><?= esc($surat['tujuan']) ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="no-border" style="text-align: center;">7</td>

                    <td>
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>Lamanya Perjalanan Dinas</li>
                            <li>Tanggal Berangkat</li>
                            <li>Tanggal harus kembali/tiba di tempat baru</li>
                        </ul>
                    </td>
                    <td colspan="2">
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>
                                <?php
                                if (!empty($surat['waktu_mulai']) && !empty($surat['waktu_berakhir'])) {

                                    $start = new DateTime($surat['waktu_mulai']);
                                    $end = new DateTime($surat['waktu_berakhir']);
                                    $interval = $start->diff($end)->days + 1;

                                    $days = [1 => 'satu', 2 => 'dua', 3 => 'tiga', 4 => 'empat', 5 => 'lima'];
                                    $interval_text = isset($days[$interval]) ? $days[$interval] : $interval;

                                    echo "{$interval} ({$interval_text}) hari";
                                } else {
                                    echo "Data tidak tersedia";
                                }
                                ?>
                            </li>
                            <li>
                                <?= !empty($surat['waktu_mulai']) ? tanggalIndonesia($surat['waktu_mulai']) : "Data tidak tersedia"; ?>
                            </li>
                            <li>
                                <?= !empty($surat['waktu_berakhir']) ? tanggalIndonesia($surat['waktu_berakhir']) : "Data tidak tersedia"; ?>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="no-border" style="text-align: center;">8</td>
                    <td>Pengikut: Nama</td>
                    <td style="width:20%; text-align:center">Tanggal Lahir</td>
                    <td style="width:30%; text-align:center">Keterangan</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>1.</li>
                            <li>2.</li>
                            <li>3.</li>
                            <li>4.</li>
                            <li>5.</li>
                        </ul>
                    </td>
                    <td style="width:20%"></td>
                    <td style="width:30%"></td>
                </tr>
                <tr>
                    <td class="no-border" style="text-align: center;">9</td>
                    <td>Pembenaan Anggaran
                        <ul style="list-style-type: lower-alpha; padding-left: 15px; margin: 0;">
                            <li>Instansi</li>
                            <li>Akun</li>
                        </ul>
                    </td>
                    <td colspan="2">
                        <ul style="list-style-type: lower-alpha; padding-top: 12px;">
                            <li><?= esc($pembebanan_anggaran['instansi']) ?></li>
                            <li><?= esc($pembebanan_anggaran['akun']) ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="no-border" style="text-align: center;">10</td>
                    <td>Keterangan lain-lain</td>
                    <td colspan="2"></td>
                </tr>
            </table>
            <div class="wrap">
                <div class="sign">
                    <p>Dikeluarkan di: Surabaya</p>
                    <p class="date">Pada Tanggal: <?= tanggalIndonesia($surat['created_at']) ?></p>
                    <p class="title">PEJABAT PEMBUAT KOMITMEN</p>
                    <p class="nama" style=" text-decoration: underline;"><?= esc($penanda_tangan['nama']) ?></p>
                    <p><?= esc($penanda_tangan['nip']) ?></p>
                </div>
            </div>


        </div>


        <!-- Kolom Kanan -->
        <div class="right">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
                <tr>
                    <td>

                    </td>
                    <td>
                        <p>I. Berangkat dari: Surabaya (Tempat Kedudukan)</p>
                        <p>ke: <?= esc($surat['kota_tujuan']) ?></p>
                        <p>pada tanggal: <?= !empty($surat['waktu_mulai']) ? tanggalIndonesia($surat['waktu_mulai']) : "Data tidak tersedia"; ?></p>
                        <p>a.n Kepala: <?= esc($pembebanan_anggaran['instansi']) ?> </p>
                        <div class="tes" style="display:flex; flex-direction:column; align-items:flex-end; text-align:right; margin-top:-5px; margin-right:15px">
                            <p>PEJABAT PEMBUAT KOMITMEN</p>
                            <br><br>
                            <p class="nama" style="text-decoration: underline;"><?= esc($penanda_tangan['nama']) ?></p>
                            <p><?= esc($penanda_tangan['nip']) ?></p>
                        </div>
                    </td>


                </tr>
                <tr>
                    <td>
                        <div class="tes" style="padding:4px">
                            <p>II. Tiba di: <?= esc($surat['kota_tujuan']) ?></p>
                            <p>Pada Tanggal: 20 Oktober 2024</p>
                            <p>Kepala:</p>
                            <div class="wrap" style="display:flex;justify-content:flex-end;text-align:center;margin-top:-10px;">
                                <p>Azana Style</p>
                                <p>Front One Hotel Pamekasan - Madura</p>
                                <br>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="tes" style="padding:4px">
                            <p>Berangkat dari:</p>
                            <p>Ke: Surabaya</p>
                            <p>Pada Tanggal:<?= tanggalIndonesia($surat['waktu_berakhir']) ?></p>
                            <p>Kepala:</p>
                            <div class="wrap" style="display:flex;justify-content:flex-end;text-align:center;margin-top:-10px;">
                                <p>Azana Style</p>
                                <p>Front One Hotel Pamekasan - Madura</p>
                                <br>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="tes" style="margin-bottom:48px">
                            <p>III. Tiba di:</p>
                            <p>Pada Tanggal:</p>
                            <p>Kepala:</p>
                        </div>
                    </td>
                    <td>
                        <div class="tes" style="margin-bottom:48px">
                            <p>Berangkat dari:</p>
                            <p>Ke:</p>
                            <p>Pada Tanggal:</p>
                            <p>Kepala:</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="tes" style="margin-bottom:48px">
                            <p>IV. Tiba di:</p>
                            <p>Pada Tanggal:</p>
                            <p>Kepala:</p>
                        </div>
                    </td>
                    <td>
                        <div class="tes" style="margin-bottom:48px">
                            <p>Berangkat dari:</p>
                            <p>Ke:</p>
                            <p>Pada Tanggal:</p>
                            <p>Kepala:</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Tiba di: Surabaya</p>
                        <p>(Tempat Kedudukan)</p>
                        <p>Pada Tanggal: <?= tanggalIndonesia($surat['waktu_berakhir']) ?></p>
                        <div class="tes" style="display:flex;justify-content:flex-end;text-align:center;margin-top:5px;">
                            <p>PEJABAT PEMBUAT KOMITMEN</p>
                            <br><br>
                            <p class="nama" style="text-decoration: underline;"><?= esc($penanda_tangan['nama']) ?></p>
                            <p><?= esc($penanda_tangan['nip']) ?></p>
                        </div>
                    </td>
                    <td>
                        <div class="tes" style="padding:10px">
                            <p style="text-align:center">Telah diberikan dengan keterangan bahwa perjalanan tersebut atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya</p>
                            <div class="tes" style="display:flex;justify-content:flex-end;text-align:center;margin-top:5px;">
                                <p>PEJABAT PEMBUAT KOMITMEN</p>
                                <br><br>
                                <p class="nama" style="text-decoration: underline;"><?= esc($penanda_tangan['nama']) ?></p>
                                <p><?= esc($penanda_tangan['nip']) ?></p>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table>

                <tr style="border:1px solid black">

                    <div class="section-title" style="padding : 4px">VI. CATATAN LAIN-LAIN:</div>
                </tr>

                <tr style="border:1px solid black">
                    <div class="section-title">VII. PERHATIAN:</div>
                    <p>Pejabat Yang Berwenang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, serta bendaharawan bertanggung jawab berdasarkan peraturan Keuangan Negara apabila negara mengalami kerugian akibat kesalahan, kelalaian, dan kealpaan.</p>
                </tr>
            </table>

        </div>
    </div>

    <div class="page-break"></div>
</body>


</html>