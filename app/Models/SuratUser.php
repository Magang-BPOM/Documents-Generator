<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratUser extends Model
{
    protected $table            = 'surat_user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['surat_id', 'user_id', 'id_created'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function surat()
    {
        $suratQuery = $this->select('
            DISTINCT(surat.id) as surat_id,
            surat.nomor_surat,
            surat.waktu_mulai,
            surat.penanda_tangan,
            surat.jabatan_ttd
        ')
            ->join('surat', 'surat_user.surat_id = surat.id')
            ->where('surat.status', 'aktif')
            ->findAll();

        $result = [];
        foreach ($suratQuery as $surat) {
            $users = $this->select('user.nama, user.nip')
                ->join('user', 'surat_user.user_id = user.id')
                ->where('surat_user.surat_id', $surat['surat_id']) 
                ->findAll();

                $kepada = [];
                foreach ($users as $user) {
                    $kepada[] = $user['nama'] . ' | ' . $user['nip'];
                }
                
                $result[] = [
                    'id' => $surat['surat_id'],
                    'nomor_surat' => $surat['nomor_surat'],
                    'kepada' => $kepada, 
                    'waktu_mulai' => $surat['waktu_mulai'],
                    'penanda_tangan' => $surat['penanda_tangan'],
                    'jabatan_ttd' => $surat['jabatan_ttd']
                ];
        }

        return $result;
    }

    public function suratbyUser()
    {
      
        $userId = session()->get('user_id');
        $userName = session()->get('nama'); 
    
        if (!$userId || !$userName) {
            return [];
        }


        $suratQuery = $this->db->table('surat_user')
            ->select('
                DISTINCT(surat.id) as surat_id,
                surat.nomor_surat,
                surat.waktu_mulai,
                surat.penanda_tangan,
                surat.jabatan_ttd
            ')
            ->join('surat', 'surat_user.surat_id = surat.id')
            ->join('user', 'surat_user.user_id = user.id')
            ->groupStart() 
                ->where('surat_user.user_id', $userId) 
                ->orWhere("FIND_IN_SET('$userName', user.nama)")
            ->groupEnd()
            ->where('surat.status', 'aktif')
            ->get()
            ->getResultArray();
    
        $result = [];
        foreach ($suratQuery as $surat) {
            $users = $this->db->table('surat_user')
                ->select('user.nama, user.nip')
                ->join('user', 'surat_user.user_id = user.id')
                ->where('surat_user.surat_id', $surat['surat_id'])
                ->get()
                ->getResultArray();
                foreach ($users as $user) {
                    $kepada[] = $user['nama'] . ' | ' . $user['nip'];
                }
             
                
                $result[] = [
                    'id' => $surat['surat_id'],
                    'nomor_surat' => $surat['nomor_surat'],
                    'kepada' => $kepada, 
                    'waktu_mulai' => $surat['waktu_mulai'],
                    'penanda_tangan' => $surat['penanda_tangan'],
                    'jabatan_ttd' => $surat['jabatan_ttd']
                ];
                
        }
    
        return $result;
    }
    

    public function suratArsip()
    {
        $userId = session()->get('user_id');


        $suratQuery = $this->select('
                DISTINCT(surat.id) as surat_id,
                surat.nomor_surat,
                surat.waktu_mulai,
                surat.penanda_tangan,
                surat.jabatan_ttd
            ')
            ->join('surat', 'surat_user.surat_id = surat.id')
            ->where('surat.status', 'arsip')
            ->findAll();


        $result = [];
        foreach ($suratQuery as $surat) {

            $users = $this->select('user.nama, user.nip')
                ->join('user', 'surat_user.user_id = user.id')
                ->where('surat_user.surat_id', $surat['surat_id'])
                ->findAll();


            $kepada = [];
            foreach ($users as $user) {
                $kepada[] = $user['nama'] . ' | ' . $user['nip'];
            }


            $result[] = [
                'id' => $surat['surat_id'],
                'nomor_surat' => $surat['nomor_surat'],
                'kepada' => implode(' ; ', $kepada),
                'waktu_mulai' => $surat['waktu_mulai'],
                'penanda_tangan' => $surat['penanda_tangan'],
                'jabatan_ttd' => $surat['jabatan_ttd']
            ];
        }

        return $result;
    }
}
