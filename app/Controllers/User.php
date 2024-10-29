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

        $validation->setRules([
            'identifier' => 'required',
            'password' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        $userModel = new ModelsUser();
        $user = $userModel->where('nip', $identifier)->first();

        if ($user && password_verify($password, $user['password'])) {

            $session->set([
                'user_id' => $user['id'],
                'username' => $user['nama'],
                'foto_profil' => $user['foto_profil'],
                'is_logged_in' => true,
            ]);

            return redirect()->to('/dashboard');
            // $all_userdata = $session->get(); // Mengambil semua data session
            // print_r($all_userdata); // Menampilkan semua data session

        } else {
            $session->setFlashdata('status', 'NIP atau Password salah.');
            return redirect()->back()->withInput();
        }
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
