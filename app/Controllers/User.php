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

        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function listuser()
    {

        $suratUser = new ModelsUser();
        $data['user'] = $suratUser->user();

        return view('pages/admin/listuser/index', $data);
    }

    public function create()
    {
        return view('pages/admin/listuser/create');
    }


    public function store()
    {
        // Inisialisasi validasi
        $validation = \Config\Services::validation();
    
        // Atur aturan validasi
        $validation->setRules([
            'nama'        => 'required',
            'nip'         => 'required|min_length[8]|max_length[100]|is_unique[user.nip]',
            'jabatan'     => 'required',
            'pangkat'     => 'permit_empty|max_length[100]',
            'foto_profil' => 'uploaded[foto_profil]|is_image[foto_profil]|mime_in[foto_profil,image/jpg,image/jpeg,image/png]|max_size[foto_profil,1024]', // Validasi file
            'password'    => 'required|min_length[6]|max_length[255]',
            'role'        => 'required|in_list[admin,pegawai]',
        ]);
    
        // Log input untuk debugging
        log_message('debug', 'Input data: ' . json_encode($this->request->getPost()));
    
        // Validasi input
        if (!$validation->run($this->request->getPost())) {
            log_message('error', 'Validation errors: ' . json_encode($validation->getErrors()));
            return redirect()->to('/user/create')->withInput()->with('validation', $validation);
        }
    
        // Default foto profil
        $fotoProfilPath = 'https://i.pravatar.cc/150?img=1';
    
        // Proses upload file
        $file = $this->request->getFile('foto_profil');
        if ($file->isValid() && !$file->hasMoved()) {
            $uploadDir = WRITEPATH . 'uploads';
    
            // Buat folder jika tidak ada
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $uploadDir);
                } else {
                    log_message('info', 'Upload directory created: ' . $uploadDir);
                }
            }
    
            $newName = $file->getRandomName();
            try {
                $file->move($uploadDir, $newName);
                $fotoProfilPath = base_url('uploads/' . $newName);
                log_message('debug', 'File uploaded successfully: ' . $fotoProfilPath);
            } catch (\Exception $e) {
                log_message('error', 'File upload error: ' . $e->getMessage());
                return redirect()->to('/user/create')->with('error', 'Failed to upload file.');
            }
        } else {
            log_message('error', 'File upload invalid or already moved.');
        }
    
        // Siapkan data untuk disimpan
        $data = [
            'nama'        => $this->request->getPost('nama'),
            'nip'         => $this->request->getPost('nip'),
            'jabatan'     => $this->request->getPost('jabatan'),
            'pangkat'     => $this->request->getPost('pangkat'),
            'foto_profil' => $fotoProfilPath,
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'        => $this->request->getPost('role'),
            'created_at'  => date('Y-m-d H:i:s'),
        ];
    
        // Log data yang akan disimpan
        log_message('debug', 'User data to be inserted: ' . json_encode($data));
    
        // Simpan ke database
        $userModel = new \App\Models\User();
        if ($userModel->insert($data)) {
            log_message('info', 'User successfully added: ' . json_encode($data));
            return redirect()->to('/admin/listuser')->with('success', 'User berhasil ditambahkan!');
        } else {
            log_message('error', 'Failed to add user: ' . json_encode($userModel->errors()));
            return redirect()->to('/user/create')->with('error', 'Gagal menambahkan user');
        }
    }
    
    
    public function updateuser()
    {
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
        $userData = [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'jabatan' => $this->request->getPost('jabatan'),
            'pangkat' => $this->request->getPost('pangkat'),
            'role' => $this->request->getPost('role'),
        ];

        $userModel = new ModelsUser();
        $userModel->update($this->request->getPost('id'), $userData);

        return $this->response->setJSON([
            'success' => true
        ]);
    }

    
    public function delete()
    {
        $userModel = new ModelsUser();

        $request = $this->request->getJSON();
        $selectedIds = $request->selectedIds ?? [];

        if (empty($selectedIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $userModel->whereIn('id', $selectedIds)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diarsipkan']);
    }



    public function login()
    {
        $session = session();
        $validation = \Config\Services::validation();

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

        if ($user && password_verify($password, $user['password'])) {
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
