<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perjalanan Dinas</title>
    <style>
        @page {
            size: 330mm 210mm;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 36px;
            padding: 0;
            font-size: 14px;
            box-sizing: border-box;
        }

        p{
            font-size: 12px;
        }

        .container {
            display: flex;
            flex-direction: row;
            width: 100%;
            border: 1px solid black;
        }

        .left, .right {
            width: 50%;
            padding: 10px;
            gap: 10px;
            box-sizing: border-box;
            border-right: 1px solid black;
        }

        .right {
            border-right: none;
        }

        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            padding: 5px;
            vertical-align: top;
        }
        td {
            width: 50%;
            border: 1px solid black;
            padding: 4px;
            margin: 0; 
            font-size: 12px;
            box-sizing: border-box; 
            vertical-align: top;
        }
        tr{
            width: 50%;
        }
        .section-title {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .sign {
            text-align: left;
        }

        .sign p.date {
            border-bottom: 2px solid black;
            padding-bottom: 5px; 
            margin-bottom: 10px; 
        }

        .sign p.title {
            margin-bottom: 40px; 
        }

        .sign p.nama {
            border-bottom: 1px solid black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="header">
                <p>BALAI BESAR PENGAWAS OBAT DAN MAKANAN</p>
                <p>Jl. Karang Menjangan No.20</p>
                <p>SURABAYA</p>
                <p>SURAT PERJALANAN DINAS (SPD)</p>
                <p>Nomor: <?= esc($surat['nomor_surat']) ?></p>
            </div>

            <table>
                <tr>
                    <td>1. Pejabat Pembuat Komitmen</td>
                    <td colspan="2"><?= esc($surat['penanda_tangan']) ?></td>
                </tr>
                <tr>
                    <td>2. Nama / NIP Pegawai yang melaksanakan perjalanan dinas</td>
                    <td colspan="2">
                        <p><?= esc($users['nama']) ?></p>
                        <p>NIP: <?= esc($users['nip']) ?></p>
                    </td>
                </tr>
                <tr>
                    <td>3.
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>Pangkat dan Golongan</li>
                            <li>Jabatan / Instansi</li>
                            <li>Tingkat Biaya Perjalanan Dinas</li>
                        </ul>
                    </td>

                    <td colspan="2">
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li><?= esc($users['nama']) ?></li>
                            <li>Pengemudi Balai Besar POM di Surabaya</li>
                            <li>-</li>
                        </ul>
                    </td>
                    
                </tr>
                <tr>
                    <td>4. Maksud Perjalanan Dinas</td>
                    <td colspan="2">Melaksanakan Tugas Sebagai Panitia Pelaksanaan Seleksi CPNS Badan POM 2024 di Pamekasan</td>
                </tr>
                <tr>
                    <td>5. Alat Angkutan yang Dipergunakan</td>
                    <td colspan="2">Kendaraan Dinas</td>
                </tr>
                <tr>
                    <td>6. <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>Tempat Berangkat</li>
                            <li>Tujuan</li>
                        </ul>
                    </td>
                 
                    <td colspan="2">
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>Surabaya</li>
                            <li>Pamekasan</li>
                        </ul>

                    </td>
                </tr>
                <tr>
                    <td>7.   <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                        <li>Lamanya Perjalanan Dinas</li>
                        <li>Tanggal Berangkat</li>
                        <li>Tanggal harus kembali/tiba di tempat baru</li>
                    </ul></td>
                    <td colspan="2">
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>2 (dua) hari</li>
                            <li>20 Oktober 2024</li>
                            <li>21 Oktober 2024</li>
                        </ul>
                    </td>
                
                </tr>
                <tr>
                    <td style="border-top: 0; width: 50%;">8. Pengikut: Nama</td>
                    <td style="border-top: 0;width: 20%;text-align: center;">Tanggal Lahir</td>
                    <td style="border-top: 0;width: 30%;text-align: center;">Keterangan</td>
                </tr>
                <tr>
                    <td>
                        <ul style="list-style: none; padding-left: 10px; margin: 0;">
                            <li>1.</li>
                            <li>2.</li>
                            <li>3.</li>
                            <li>4.</li>
                            <li>5.</li>
                        </ul>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>9. Pembenaan Anggaran
                        <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                            <li>Instansi</li>
                            <li>Akun</li>
                        </ul>
                    </td>
                    <td colspan="2"> <ul style="list-style-type: lower-alpha; padding-left: 20px; margin: 0;">
                        <li>Balai Besar Pengawas Obat dan Makanan di Surabaya</li>
                        <li>3165.BKB.001.052.F.524111</li>
                    </ul></td>
                </tr>
                <tr>
                    <td>10. Keterangan lain-lain</td>
                    <td colspan="2"></td>
                </tr>

            </table>
            <table style="border-top: none; width: 100%;">
            </table>
           

            <div class="container" style="display: flex;
            justify-content: flex-end;
            margin-right: 130px;
            padding: 0;
            width: 100%;">
                <div class="sign">
                    <p class>Dikeluarkan di: Surabaya</p>
                    <p class="date">Pada Tanggal: 21 Oktober 2024</p>
                    <p class="title">PEJABAT PEMBUAT KOMITMEN</p>
                    <p class="nama"><strong>Anita Yuni Puji Astutik, S.Kom</strong></p>
                    <p>NIP. 19870628 201012 2 006</p>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan -->
        <div class="right">
            <table>
                <!-- Baris 1 -->
                <tr>
                    <td>
                       
                    </td>
                    <td>
                        <p>I. Berangkat dari: Surabaya (Tempat Kedudukan)</p>
                        <p>ke: Pamekasan</p>
                        <p>pada tanggal: 20 Oktober 2024</p>
                        <p>a.n Kepala: ADADSAAK</p>
                        <div class="container" style="border: none;">

                            <div class="sign">
                                <p>Pejabat Pembuat Komitmen</p>
                                <p><strong>Anita Yuni Puji Astutik, S.Kom</strong></p>
                                <p>NIP. 19870628 201012 2 006</p>
                            </div>
                        </div>
                    </td>
                </tr>
                <!-- Baris 2 -->
                <tr>
                    <td>
                        <p>II. Tiba di: Pamekasan</p>
                        <p>Pada Tanggal: 20 Oktober 2024</p>
                        <p>Kepala:</p>
                        <p>Azana Style Front One Hotel Pamekasan - Madura</p>
                    </td>
                    <td>
                        <p>II. Tiba di: Pamekasan</p>
                        <p>Pada Tanggal: 20 Oktober 2024</p>
                        <p>Kepala:</p>
                        <p>Azana Style Front One Hotel Pamekasan - Madura</p>
                    </td>
                </tr>

                <!-- Baris 3 -->
                <tr>
                    <td>
                        <p>III. Tiba di:</p>
                        <p>Pada Tanggal:</p>
                        <p>Kepala:</p>
                    </td>
                    <td>
                        <p>III. Tiba di:</p>
                        <p>Pada Tanggal:</p>
                        <p>Kepala:</p>
                    </td>
                </tr>

                <!-- Baris 4 -->
                <tr>
                    <td>
                        <p>IV. Tiba di:</p>
                        <p>Pada Tanggal:</p>
                        <p>Kepala:</p>
                    </td>
                    <td>
                        <p>IV. Tiba di:</p>
                        <p>Pada Tanggal:</p>
                        <p>Kepala:</p>
                    </td>
                </tr>

                <!-- Baris 5 -->
                <tr>
                    <td>
                        <p>Tiba kembali di: Surabaya</p>
                        <p>Pada Tanggal: 21 Oktober 2024</p>
                    </td>
                    <td>
                        <div class="sign">
                            <p>PEJABAT PEMBUAT KOMITMEN</p>
                            <p><strong>Anita Yuni Puji Astutik, S.Kom</strong></p>
                            <p>NIP. 19870628 201012 2 006</p>
                        </div>
                    </td>
                </tr>
            </table>


            <div class="section-title">VI. CATATAN LAIN-LAIN:</div>
            <p></p>

            <div class="section-title">VII. PERHATIAN:</div>
            <p>Pejabat Yang Berwenang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, serta bendaharawan bertanggung jawab berdasarkan peraturan Keuangan Negara apabila negara mengalami kerugian akibat kesalahan, kelalaian, dan kealpaan.</p>
        </div>
    </div>
</body>
</html>
