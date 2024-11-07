<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User as ModelsUser;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $validation = \Config\Services::validation();
    
        $identifier = $this->request->getVar('identifier');
        $password = $this->request->getVar('password');
    
        // Set validasi input
        $validation->setRules([
            'identifier' => 'required',
            'password' => 'required',
        ]);
    
        // Cek validasi
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }
    
        $userModel = new ModelsUser();
        $user = $userModel->where('nip', $identifier)->first();
    
        // Cek jika user ada dan password benar
        if ($user && password_verify($password, $user['password'])) {
            // Set session data untuk pengguna yang login
            $session->set([
                'user_id' => $user['id'],
                'username' => $user['nama'],
                'foto_profil' => $user['foto_profil'],
                'is_logged_in' => true,
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
        session()->destroy();
        return redirect()->to('/login');
    }
}
