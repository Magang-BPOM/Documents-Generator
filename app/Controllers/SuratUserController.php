<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SuratUser;

class SuratUserController extends BaseController
{
    public function index()
    {
        $suratUser = new SuratUser();
        $data['surat_user'] = $suratUser->surat();
        // dd($data);
        var_dump($data);
        // die();
        return view('pages/surat', $data);
    }
}
