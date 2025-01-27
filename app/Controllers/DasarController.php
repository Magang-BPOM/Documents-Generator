<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Dasar as Dasar;
use CodeIgniter\HTTP\ResponseInterface;

class DasarController extends BaseController
{
    protected $dasarModel;
    public function __construct()
    {
        $this->dasarModel = new Dasar();
    }
    public function index()
    {
        $data['dasar'] = $this->dasarModel->findAll();

        return view('pages/admin/dasar/index', $data);
    }

    public function store()
    {
        $undang = $this->request->getPost('undang');
        $this->dasarModel->save(['undang' => $undang]);
        return redirect()->to('/dasar');
    }

    public function update($id)
    {
        $undang = $this->request->getPost('undang');

        $data = [
            'undang' => $undang,
        ];

        if ($this->dasarModel->update($id, $data)) {
            return redirect()->to('/dasar')->with('success', 'Undang-Undang berhasil diperbarui');
        } else {
            return redirect()->to('/dasar')->with('error', 'Gagal memperbarui Undang-Undang');
        }
    }

    public function delete($id)
    {
        $dasar = $this->dasarModel->find($id); // Menemukan data berdasarkan ID

        if (!$dasar) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $this->dasarModel->delete($id); // Menghapus data

        return redirect()->to('/dasar')->with('success', 'Data berhasil dihapus');
    }
}
