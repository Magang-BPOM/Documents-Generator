<?php

namespace App\Controllers;

use App\Models\PembabananAnggaranModel;
use App\Models\Surat as Surat;
use App\Models\SuratSinggah;
use App\Models\User as User;
use App\Models\Dasar as Dasar;
use App\Models\DasarSurat as DasarSurat;
use App\Models\RincianBiayaModel;
use App\Models\SuratUser as SuratUser;
use Dompdf\Dompdf;
use Dompdf\Options;
use \ConvertApi\ConvertApi;
use DateTime;
use IntlDateFormatter;

class WordController extends BaseController
{
    protected $suratModel;
    protected $userModel;
    protected $dasarModel;
    protected $dasarSurat;
    protected $SuratUserModel;
    protected $pembebanan_anggaran;

    public function __construct()
    {
        $this->suratModel = new Surat();
        $this->userModel = new User();
        $this->SuratUserModel = new SuratUser();
        $this->dasarModel = new Dasar();
        $this->dasarSurat = new DasarSurat();
        $this->pembebanan_anggaran = new PembabananAnggaranModel();
    }

    public function generateDocx($suratId)
    {
        helper('url');

        // Ambil data surat berdasarkan $suratId
        $suratModel = new Surat();
        $surat = $suratModel->find($suratId);
        if (!$surat) {
            log_message('error', "Surat with ID $suratId not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Surat with ID $suratId not found.");
        }

        // Ambil pengguna terkait surat dari tabel surat_user
        $suratUserModel = new SuratUser();

        $users = $suratUserModel
            ->select('user.*')
            ->join('user', 'user.id = surat_user.user_id')
            ->where('surat_user.surat_id', $suratId)
            ->findAll();

        // header('Content-Type: application/json');
        // echo json_encode($users);
        // exit;

        // Ambil daftar dasar hukum surat
        $DasarSuratModel = new DasarSurat();
        $listdasar = $DasarSuratModel
            ->select('dasar.*')
            ->join('dasar', 'dasar.id = dasarsurat.id_dasar')
            ->where('dasarsurat.id_surat', $suratId)
            ->findAll();

        // Ambil data penanda tangan
        $userModel = new User();
        $penandaTangan = $userModel->find($surat['id_penanda_tangan']);
        if (!$penandaTangan) {
            log_message('error', "Penanda tangan for ID {$surat['id_penanda_tangan']} not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Penanda tangan not found.");
        }


        // Inisialisasi PhpWord dan set konfigurasi default dokumen
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Bookman Old Style');
        $phpWord->setDefaultFontSize(12);

        // Tambahkan halaman dokumen dengan pengaturan margin dan ukuran
        $section = $phpWord->addSection([
            'marginLeft' => 1440,
            'marginRight' => 1440,
            'marginTop' => 3000,
            'marginBottom' => 0,
            'pageSizeW' => 11906,
            'pageSizeH' => 18700,
            'differentFirstPageHeaderFooter' => true,

        ]);

        // Tambahkan header dokumen dengan gambar
        $header = $section->addHeader();
        $header->firstPage();
        $header->addImage(FCPATH . 'header.jpg', [
            'width' => 560,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'marginLeft' => -55,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
        ]);

        // Membuat tabel untuk konten utama surat
        $table = $section->addTable([
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellMargin' => 20,
            'width' => 1000,
        ]);

        // Tambahkan baris untuk judul surat
        $table->addRow();
        $titleCell = $table->addCell(10000, ['gridSpan' => 3]);
        $titleCell->addText('SURAT TUGAS', ['size' => 12], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Tambahkan nomor surat
        $table->addRow();
        $numberCell = $table->addCell(10000, ['gridSpan' => 3]);
        $numberCell->addText('NOMOR: ' . ($surat['nomor_surat'] ?? ''), [], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $table->addRow();
        $table->addCell(10000, ['gridSpan' => 3])->addTextBreak();

        // Tambahkan bagian menimbang
        $table->addRow();
        $table->addCell(2000)->addText('Menimbang');
        $table->addCell(200)->addText(':', []);
        $table->addCell(7800)->addText(($surat['menimbang'] ?? ''), [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]); // Column 3

        // Tambahkan daftar dasar surat
        $table->addRow();
        $table->addCell(2000)->addText('Dasar');
        $table->addCell(200)->addText(':', []);
        $dasarCell = $table->addCell(7800);
        if (!empty($listdasar)) {
            $i = 1;
            foreach ($listdasar as $dasar) {
                $dasarCell->addText("{$i}. {$dasar['undang']}", [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
                $i++;
            }
        } else {
            $dasarCell->addText('Tidak ada dasar yang ditemukan.', [], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        }

        // Tambahkan daftar penerima tugas
        $table->addRow();
        $memberiTugasCell = $table->addCell(10000, ['gridSpan' => 3]);
        $memberiTugasCell->addText('Memberi Tugas', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $table->addRow();
        $table->addCell(2000)->addText('Kepada');
        $table->addCell(200)->addText(':', []);
        $kepadaCell = $table->addCell(7800);

        if (count($users) > 2) {
            // Jika lebih dari 2 user, tampilkan hanya teks ini di halaman pertama
            $kepadaCell->addText("Nama-nama terlampir", [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);

            // Format rentang tanggal tugas
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


            $waktuMulai = $surat['waktu_mulai'] ?? '';
            $waktuBerakhir = $surat['waktu_berakhir'] ?? '';

            $waktuRentang = formatTanggalRentang($waktuMulai, $waktuBerakhir);

            // Tambahkan bagian waktu, tujuan, dan rincian lainnya
            $table->addRow();
            $table->addCell(2000)->addText('Untuk');
            $table->addCell(200)->addText(':', []);
            $untukCell = $table->addCell(7800);
            $untukCell->addText('1. Sebagai: ' . ($surat['sebagai'] ?? ''), [], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
            $untukCell->addText(
                "2. Waktu: {$waktuRentang}",
                [],
                ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]
            );
            $untukCell->addText('3. Tujuan: ' . ($surat['tujuan'] ?? ''), [], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);

            $table->addRow();
            $signatureCell = $table->addCell(10000, ['spaceAfter' => 0, 'gridSpan' => 3, 'alignment' => 'right']);
            $signatureCell->addTextBreak();


            // Tambahkan tanda tangan dan footer
            $section->addTextBreak(3);
            $signatureTable = $section->addTable([
                'spaceAfter' => 0,
                'alignment' => 'right',
            ]);

            $signatureTable->addRow();
            $signatureCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $signatureCell->addText(
                'Surabaya, ' . date('d F Y', strtotime($surat['waktu_mulai'] ?? date('Y-m-d'))),
                ['size' => 12],
                ['spaceAfter' => 0, 'alignment' => 'right']
            );

            $signatureTable->addRow();
            $positionCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $positionCell->addText(
                $penandaTangan['jabatan'] ?? '',
                ['size' => 12],
                ['spaceAfter' => 0, 'alignment' => 'right']
            );

            $signatureTable->addRow();
            $emptyCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $emptyCell->addTextBreak(3);

            $signatureTable->addRow();
            $nameCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $nameCell->addText(
                $penandaTangan['nama'] ?? '',
                ['size' => 12],
                ['spaceAfter' => 0, 'alignment' => 'right']
            );
            $section->addTextBreak(2);

            $section->addTextBox(
                [
                    'borderSize' => 1,
                    'borderColor' => '000000',
                    'width' => 452,
                    'height' => 40,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'valign' => 'center',
                ]
            )->addText(
                'Petugas tidak diperkenankan menerima gratifikasi dalam bentuk apapun.',

                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 0,
                    'line-height' => 1,
                ]
            );



            $footer = $section->addFooter();
            $footer->firstPage();
            $footer->addImage(FCPATH . 'end.jpg', [
                'width' => 600,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -75,
                'marginTop' => -142,
                'wrappingStyle' => 'behind'
            ]);

            // Tambahkan halaman baru
            $section->addPageBreak();

            // Buat judul daftar nama
            $section->addText('LAMPIRAN', ['size' => 14]);
            $section->addText('SURAT TUGAS KEPALA BBPOM DI SURABAYA', ['size' => 14]);
            $section->addText('NOMOR  : ' . $surat['nomor_surat'], ['size' => 14]);
            $section->addText('TANGGAL: ' . $surat['waktu_mulai'], ['size' => 14]);
            $section->addTextBreak(1);

            // Buat tabel baru untuk daftar nama
            $table = $section->addTable();

            // Tambahkan header tabel dengan lebar yang lebih proporsional
            $table->addRow();
            $table->addCell(600, ['bgColor' => 'D3D3D3'])->addText("No", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(2500, ['bgColor' => 'D3D3D3'])->addText("Nama", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(2000, ['bgColor' => 'D3D3D3'])->addText("NIP", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1800, ['bgColor' => 'D3D3D3'])->addText("Pangkat/Gol", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(2000, ['bgColor' => 'D3D3D3'])->addText("Jabatan", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

            // Tambahkan data user ke dalam tabel
            $i = 1;
            foreach ($users as $user) {
                $table->addRow();
                $table->addCell(600)->addText($i, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(2500)->addText($user['nama']);
                $table->addCell(2000)->addText($user['nip']);
                $table->addCell(1800)->addText($user['pangkat']);

                // Batasi panjang teks "Jabatan" dan aktifkan word wrap
                $jabatanText = wordwrap($user['jabatan'], 20, "\n", true);
                $table->addCell(2000, ['valign' => 'center'])->addText($jabatanText);

                $i++;
            }

            // TTD user 3
            $section->addTextBreak(3);
            // Tambahkan tanda tangan dan footer
            $signatureTable = $section->addTable([
                'spaceAfter' => 0,
                'alignment' => 'right',
            ]);

            $signatureTable->addRow();
            $signatureCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $signatureCell->addText(
                'Surabaya, ' . date('d F Y', strtotime($surat['waktu_mulai'] ?? date('Y-m-d'))),
                ['size' => 12],
                ['spaceAfter' => 0, 'alignment' => 'right']
            );

            $signatureTable->addRow();
            $positionCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $positionCell->addText(
                $penandaTangan['jabatan'] ?? '',
                ['size' => 12],
                ['spaceAfter' => 0, 'alignment' => 'right']
            );

            $signatureTable->addRow();
            $emptyCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $emptyCell->addTextBreak(3);

            $signatureTable->addRow();
            $nameCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $nameCell->addText(
                $penandaTangan['nama'] ?? '',
                ['size' => 12],
                ['spaceAfter' => 0, 'alignment' => 'right']
            );

            $footer2 = $section->addFooter();
            $footer2->addImage(FCPATH . 'end.jpg', [
                'width' => 600,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -75,
                'marginTop' => -142,
                'wrappingStyle' => 'behind'
            ]);
        } else {
            // Jika jumlah user 2 atau kurang, langsung tampilkan semua di halaman pertama
            $i = 1;
            foreach ($users as $user) {
                $kepadaCell->addText("{$i}. Nama: {$user['nama']}", [], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
                $kepadaCell->addText("    NIP: {$user['nip']}", [], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
                $kepadaCell->addText("    Pangkat/Gol: {$user['pangkat']}", [], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
                $kepadaCell->addText("    Jabatan: {$user['jabatan']}", [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
                $i++;
            }
            // Format rentang tanggal tugas
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


            $waktuMulai = $surat['waktu_mulai'] ?? '';
            $waktuBerakhir = $surat['waktu_berakhir'] ?? '';

            $waktuRentang = formatTanggalRentang($waktuMulai, $waktuBerakhir);

            // Tambahkan bagian waktu, tujuan, dan rincian lainnya
            $table->addRow();
            $table->addCell(2000)->addText('Untuk');
            $table->addCell(200)->addText(':', []);
            $untukCell = $table->addCell(7800);
            $untukCell->addText('1. Sebagai: ' . ($surat['sebagai'] ?? ''), [], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
            $untukCell->addText(
                "2. Waktu: {$waktuRentang}",
                [],
                ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]
            );
            $untukCell->addText('3. Tujuan: ' . ($surat['tujuan'] ?? ''), [], ['spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);

            $table->addRow();
            $signatureCell = $table->addCell(10000, ['spaceAfter' => 0, 'gridSpan' => 3, 'alignment' => 'right']);
            $signatureCell->addTextBreak();


            // Tambahkan tanda tangan dan footer
            $section->addTextBreak(3);
            $signatureTable = $section->addTable([
                'spaceAfter' => 0,
                'alignment' => 'right',
            ]);

            $signatureTable->addRow();
            $signatureCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $signatureCell->addText(
                'Surabaya, ' . date('d F Y', strtotime($surat['waktu_mulai'] ?? date('Y-m-d'))),
                ['size' => 12],
                ['spaceAfter' => 0, 'alignment' => 'right']
            );

            $signatureTable->addRow();
            $positionCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $positionCell->addText(
                $penandaTangan['jabatan'] ?? '',
                ['size' => 12],
                ['spaceAfter' => 0, 'alignment' => 'right']
            );

            $signatureTable->addRow();
            $emptyCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $emptyCell->addTextBreak(3);

            $signatureTable->addRow();
            $nameCell = $signatureTable->addCell(10000, [
                'spaceAfter' => 0,
                'gridSpan' => 3,
                'alignment' => 'right',
            ]);
            $nameCell->addText(
                $penandaTangan['nama'] ?? '',
                ['size' => 12],
                ['spaceAfter' => 0, 'alignment' => 'right']
            );
            $section->addTextBreak(2);

            $section->addTextBox(
                [
                    'borderSize' => 1,
                    'borderColor' => '000000',
                    'width' => 452,
                    'height' => 40,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'valign' => 'center',
                ]
            )->addText(
                'Petugas tidak diperkenankan menerima gratifikasi dalam bentuk apapun.',

                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 0,
                    'line-height' => 1,
                ]
            );



            $footer = $section->addFooter();
            $footer->firstPage();
            $footer->addImage(FCPATH . 'end.jpg', [
                'width' => 600,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -75,
                'marginTop' => -142,
                'wrappingStyle' => 'behind'
            ]);
        }

        // Simpan file Word
        $fileName = "Surat-Tugas-$suratId.docx";
        $tempFile = WRITEPATH . $fileName;

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return $this->response->download($tempFile, null)->setFileName($fileName);
    }
}
