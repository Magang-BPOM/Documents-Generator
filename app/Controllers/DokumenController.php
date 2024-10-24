<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DokumenController extends Controller
{
    public function index(): string
    {
        return view('pages/dokumen');
    }

    // Method untuk menampilkan halaman form pembuatan surat
    public function create()
    {
        return view('surat/create'); // Mengarahkan ke view form surat (create.php)
    }

    // Method untuk menangani form submit surat
    public function submit()
    {
        // Ambil input dari form
        $judul = $this->request->getPost('judul');
        $menimbang = $this->request->getPost('menimbang');
        $dasar = $this->request->getPost('dasar');
        $kepada = $this->request->getPost('kepada');
        $untuk = $this->request->getPost('untuk');

        // Lakukan proses penyimpanan data, validasi, dll. (opsional)

        // Setelah data berhasil disimpan atau diproses, bisa diarahkan ke view lain
        return view('surat/success', [
            'judul' => $judul,
            'menimbang' => $menimbang,
            'dasar' => $dasar,
            'kepada' => $kepada,
            'untuk' => $untuk
        ]);
    }
}
