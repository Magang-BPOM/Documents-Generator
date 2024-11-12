<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User as ModelsUser;
use App\Models\SuratUser as SuratUser;
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

    public function listuser()
    {

        $suratUser = new ModelsUser();
        $data['user'] = $suratUser->surat();

        return view('pages/admin/listuser/index', $data);
    }

    public function updateuser()
    {
        // Validasi input
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'nip' => 'required|is_unique[users.nip]',
            'nama' => 'required',
            'jabatan' => 'required',
            'pangkat' => 'required',
            'role' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        // Ambil data dari form
        $userData = [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'jabatan' => $this->request->getPost('jabatan'),
            'pangkat' => $this->request->getPost('pangkat'),
            'role' => $this->request->getPost('role'),
        ];

        // Update data user
        $userModel = new ModelsUser();
        $userModel->update($this->request->getPost('id'), $userData);

        // Kembalikan response
        return $this->response->setJSON([
            'success' => true
        ]);
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
                'nip' => $user['nip'],
                'role' => $user['role'],
                'logged_in' => true,
            ]);

            // Arahkan berdasarkan role
            if ($user['role'] == 'admin') {
                $session->setFlashdata('success', 'Login berhasil! Selamat datang, ' . $user['nama']);
                return redirect()->to('/admin/dashboard');
            } else {
                $session->setFlashdata('success', 'Login berhasil! Selamat datang, ' . $user['nama']);
                return redirect()->to('/dashboard');
            }
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
