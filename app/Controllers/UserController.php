<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User as ModelsUser;
use App\Models\SuratUser as SuratUser;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
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
        $validation = \Config\Services::validation();
        $session = session();

        $validation->setRules([
            'nama'        => 'required',
            'nip' => ['rules' => 'required|min_length[8]|max_length[100]|is_unique[user.nip]',
                            'errors' => [
                                'required' => 'NIP wajib diisi.',
                                'is_unique' => 'NIP sudah terdaftar, gunakan NIP lain.',
                                'min_length' => 'NIP minimal harus memiliki 8 karakter.',
                                'max_length' => 'NIP maksimal boleh memiliki 100 karakter.'
                            ]
                        ],
            'jabatan'     => 'required',
            'pangkat'     => 'permit_empty|max_length[100]',
            'foto_profil' => 'uploaded[foto_profil]|is_image[foto_profil]|mime_in[foto_profil,image/jpg,image/jpeg,image/png]|max_size[foto_profil,1024]',
            'password'    => 'required|min_length[6]|max_length[255]',
            'role'        => 'required|in_list[admin,pegawai]',
        ]);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()->withInput()
                ->with('validation', $validation)
                ->with('error', $validation->getError('nip')); 
        }
        
        $file = $this->request->getFile('foto_profil');
        $fotoProfilPath = 'https://i.pravatar.cc/150?img=1'; 

        if ($file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/user_profiles';
            // $uploadDir = WRITEPATH . 'uploads/user_profiles';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $fotoProfilPath = base_url('uploads/user_profiles/' . $newName);
        } else {
            return redirect()->back()->with('error', 'Gagal mengunggah file.');
        }

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

        $userModel = new \App\Models\User();
        if ($userModel->insert($data)) {
            $session->setFlashdata('success', 'User berhasil ditambahkan!');
            return redirect()->to('/user/create');
        } else {
            $session->setFlashdata('error', 'Gagal menambahkan user.');
            return redirect()->back()->withInput();
        }
        
    }


    public function update($id)
    {
        $validation = \Config\Services::validation();
    
        $validation->setRules([
            'nama'        => 'required',
            'nip'         => [
                'rules' => 'required|min_length[8]|max_length[100]|is_unique[user.nip, id,' . $id . ']',
                'errors' => [
                    'required' => 'NIP wajib diisi.',
                    'is_unique' => 'NIP sudah terdaftar, gunakan NIP lain.',
                    'min_length' => 'NIP minimal harus memiliki 8 karakter.',
                    'max_length' => 'NIP maksimal boleh memiliki 100 karakter.'
                ]
            ],
            'jabatan'     => 'required',
            'pangkat'     => 'permit_empty|max_length[100]',
            'foto_profil' => 'permit_empty|is_image[foto_profil]|mime_in[foto_profil,image/jpg,image/jpeg,image/png]|max_size[foto_profil,1024]',
            'role'        => 'required|in_list[admin,pegawai]',
        ]);
    

        if (!$validation->run($this->request->getPost())) {
      
            return redirect()->back()->withInput()->with('validation', $validation);
        }
        
        $userModel = new \App\Models\User();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/user/edit')->with('error', 'User tidak ditemukan');
        }
    
        $data = [
            'nama'        => $this->request->getPost('nama'),
            'nip'         => $this->request->getPost('nip'),
            'jabatan'     => $this->request->getPost('jabatan'),
            'pangkat'     => $this->request->getPost('pangkat'),
            'role'        => $this->request->getPost('role'),
        ];
  
 
        $file = $this->request->getFile('foto_profil');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/user_profiles';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $data['foto_profil'] = base_url('uploads/user_profiles/' . $newName);
        }


        if ($userModel->update($id, $data)) {
            return redirect()->to('admin/listuser')->with('success', 'User berhasil diperbarui!');
        } else {
            return redirect()->to('admin/listuser')->with('error', 'Gagal memperbarui user.');
        }
    }
    


    public function delete()
    {
        $userModel = new \App\Models\User();;

        $request = $this->request->getJSON();
        $selectedIds = $request->selectedIds ?? [];

        if (empty($selectedIds)) {
            return $this->response->setJSON(['error' => false, 'message' => 'Tidak ada data yang dipilih']);
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

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'user_id' => $user['id'],
                'nama' => $user['nama'],
                'nip' => $user['nip'],
                'foto_profil' => $user['foto_profil'],
                'role' => $user['role'],
                'logged_in' => true,
            ]);

            if ($user['role'] == 'admin') {
                $session->setFlashdata('success', 'Login berhasil! Selamat datang, ' . $user['nama']);
                return redirect()->to('/admin/dashboard');
            } else {
                $session->setFlashdata('success', 'Login berhasil! Selamat datang, ' . $user['nama']);
                return redirect()->to('/dashboard');
            }
        } else {
            $session->setFlashdata('error', 'NIP atau Password salah.');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
