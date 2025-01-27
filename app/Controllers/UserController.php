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
        $session = session();
        $characters = 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captcha = substr(str_shuffle($characters), 0, 6);
        $session->set('captcha', $captcha);

        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', ['captcha' => $captcha]);
    }

    public function listuser()
    {

        $suratUser = new ModelsUser();
        $data['user'] = $suratUser->user();
        // header('Content-Type: application/json');
        // echo json_encode($data);
        // exit;

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

        // Validasi input
        $validation->setRules([
            'nama'        => 'required',
            'nip' => [
                'rules' => 'required|min_length[8]|max_length[100]|is_unique[user.nip]',
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
            'is_penanda_tangan' => 'required',
            'is_bendahara' => 'required',
            'role'        => 'required|in_list[admin,pegawai]',
        ]);

        if (!$validation->run($this->request->getPost())) {
            // Hanya menyimpan data POST, tidak menyimpan objek file
            return redirect()->back()->withInput($this->request->getPost())
                ->with('validation', $validation)
                ->with('error', $validation->getError('nip'));
        }

        $file = $this->request->getFile('foto_profil');
        $fotoProfilPath = 'https://i.pravatar.cc/150?img=1'; // Default gambar

        // Proses upload file jika file valid
        if ($file->isValid() && !$file->hasMoved()) {
            $uploadDir = WRITEPATH . 'uploads/user_profiles'; // Gunakan WRITEPATH
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Buat folder jika tidak ada
            }

            $newName = $file->getRandomName(); // Generate nama acak
            $file->move($uploadDir, $newName); // Pindahkan file ke folder
            $fotoProfilPath = base_url('writable/uploads/user_profiles/' . $newName); // Path URL file
        } else {
            // Jika upload gagal, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Gagal mengunggah file.');
        }

        // Data yang akan disimpan ke database
        $data = [
            'nama'        => $this->request->getPost('nama'),
            'nip'         => $this->request->getPost('nip'),
            'jabatan'     => $this->request->getPost('jabatan'),
            'pangkat'     => $this->request->getPost('pangkat'),
            'foto_profil' => $fotoProfilPath,
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'        => $this->request->getPost('role'),
            'is_penanda_tangan' => $this->request->getPost('is_penanda_tangan'),
            'is_bendahara' => $this->request->getPost('is_bendahara'),
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        // Simpan data ke database
        $userModel = new \App\Models\User();
        if ($userModel->insert($data)) {
            $session->setFlashdata('success', 'User berhasil ditambahkan!');
            return redirect()->to('/admin/listuser');
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
            'is_penanda_tangan' => 'required',
            'is_bendahara' => 'required',
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
            'is_penanda_tangan' => $this->request->getPost('is_penanda_tangan'),
            'is_bendahara' => $this->request->getPost('is_bendahara'),
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

        return redirect()->to('/admin/listuser')->with('success', 'Data berhasil dihapus');
    }



    public function login()
    {
        $session = session();
        $validation = \Config\Services::validation();

        $identifier = $this->request->getVar('identifier');
        $password = $this->request->getVar('password');
        $captchaInput = $this->request->getVar('captcha');
        $captchaSession = $session->get('captcha'); // Ambil captcha dari session

        $validation->setRules([
            'identifier' => 'required',
            'password' => 'required',
            'captcha'   => 'required',
        ]);

        // Cek validasi input
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Validasi captcha
        if ($captchaInput != $captchaSession) {
            return redirect()->back()->with('error', 'Captcha is incorrect.')->withInput();
        }

        // Lanjutkan proses autentikasi seperti sebelumnya
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
