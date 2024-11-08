<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User as ModelsUser;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    public function index()
    {
        // Cek jika pengguna sudah login
        if (session()->get('logged_in')) {
            // Arahkan langsung ke dashboard jika session masih ada
            return redirect()->to('/dashboard');
        }

        // Jika belum login, tampilkan halaman login
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $validation = \Config\Services::validation();

        // Mengambil data input
        $identifier = $this->request->getVar('identifier');
        $password = $this->request->getVar('password');

        // Atur aturan validasi input
        $validation->setRules([
            'identifier' => 'required',
            'password' => 'required',
        ]);

        // Cek validasi
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Cari user berdasarkan NIP
        $userModel = new ModelsUser();
        $user = $userModel->where('nip', $identifier)->first();

        // Cek jika user ada dan password benar
        if ($user && password_verify($password, $user['password'])) {
            // Set data session untuk pengguna yang login
            $session->set([
                'user_id' => $user['id'],
                'nama' => $user['nama'],
                'nip' => $user['nip'], // Menyimpan NIP di session
                'logged_in' => true,
            ]);

            // Set flash message untuk login berhasil
            $session->setFlashdata('success', 'Login berhasil! Selamat datang, ' . $user['nama']);
            return redirect()->to('/dashboard');
        } else {
            // Set flash message untuk login gagal
            $session->setFlashdata('error', 'NIP atau Password salah.');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        // Hapus semua data session
        session()->destroy();
        return redirect()->to('/');
    }
}
