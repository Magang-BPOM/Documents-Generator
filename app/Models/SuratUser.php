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
    protected $allowedFields    = ['surat_id', 'user_id', 'id_created', 'is_read'];

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
                surat.id_penanda_tangan
            ')
            ->join('surat', 'surat_user.surat_id = surat.id')
            ->where('surat.status', 'aktif')
            ->orderBy('surat.created_at', 'DESC')
            ->findAll();

        $result = [];
        foreach ($suratQuery as $surat) {
            $users = $this->select('user.nama, user.nip, surat_user.is_read')
                ->join('user', 'surat_user.user_id = user.id')
                ->where('surat_user.surat_id', $surat['surat_id'])
                ->findAll();

            $kepada = [];
            foreach ($users as $user) {
                $kepada[] = [
                    'nama' => $user['nama'],
                    'nip' => $user['nip'],
                    'is_read' => $user['is_read'],
                ];
            }

            $penandaTangan = $this->db->table('user')
                ->select('nama, nip, jabatan')
                ->where('id', $surat['id_penanda_tangan'])
                ->get()
                ->getRowArray();

            $result[] = [
                'id' => $surat['surat_id'],
                'nomor_surat' => $surat['nomor_surat'],
                'kepada' => $kepada,
                'waktu_mulai' => $surat['waktu_mulai'],
                'penanda_tangan' => $penandaTangan ? $penandaTangan['nama'] : null,
                'nip_penanda_tangan' => $penandaTangan ? $penandaTangan['nip'] : null,
                'jabatan_penanda_tangan' => $penandaTangan ? $penandaTangan['jabatan'] : null,
            ];
        }

        return $result;
    }

    public function suratDetail($id)
    {
        
    }


    public function suratbyUser()
    {
        $userId = session()->get('user_id'); 
        $userName = session()->get('nama');

        if (!$userId || !$userName) {
            return []; 
        }

        // Query untuk mengambil dokumen yang sesuai dengan kriteria
        $suratQuery = $this->db->table('surat_user su')
            ->select('
            DISTINCT(s.id) as surat_id,
            s.nomor_surat,
            s.waktu_mulai,
            s.id_penanda_tangan,
            s.status,
            s.created_at,
            su.is_read,
            su.user_id
        ')
            ->join('surat s', 'su.surat_id = s.id') 
            ->join('user u', 'su.user_id = u.id')
            ->groupStart()
            ->where('su.user_id', $userId) 
            ->orWhere('u.nama', $userName) 
            ->groupEnd()
            ->where('s.status', 'aktif') 
            ->orderBy('s.created_at', 'DESC') 
            ->get()
            ->getResultArray();

        $result = [];

        foreach ($suratQuery as $surat) {
            // Ambil semua user yang terkait dengan surat
            $users = $this->db->table('surat_user su')
                ->select('u.nama, u.nip, su.user_id, su.is_read')
                ->join('user u', 'su.user_id = u.id')
                ->where('su.surat_id', $surat['surat_id'])
                ->get()
                ->getResultArray();

            $kepada = [];
            foreach ($users as $user) {
                $kepada[] = [
                    'user_id' => $user['user_id'],
                    'nama' => $user['nama'],
                    'nip' => $user['nip'],
                    'is_read' => isset($user['is_read']) ? (int) $user['is_read'] : 0,
                ];
            }

            // Ambil data penanda tangan
            $penandaTangan = $this->db->table('user')
                ->select('nama, nip, jabatan')
                ->where('id', $surat['id_penanda_tangan'])
                ->get()
                ->getRowArray();

            $result[] = [
                'id' => $surat['surat_id'],
                'nomor_surat' => $surat['nomor_surat'],
                'kepada' => $kepada,
                'waktu_mulai' => $surat['waktu_mulai'],
                'penanda_tangan' => $penandaTangan ? $penandaTangan['nama'] : null,
                'nip_penanda_tangan' => $penandaTangan ? $penandaTangan['nip'] : null,
                'jabatan_penanda_tangan' => $penandaTangan ? $penandaTangan['jabatan'] : null,
                'created_at' => $surat['created_at'],
            ];
        }

        return $result;
    }

    public function Read($id)
    {
        return $this->update($id, ['is_read' => true]);
    }


    public function suratArsip()
    {
        $userId = session()->get('user_id');

        $suratQuery = $this->select('
                DISTINCT(surat.id) as surat_id,
                surat.nomor_surat,
                surat.waktu_mulai,
                surat.id_penanda_tangan
            ')
            ->join('surat', 'surat_user.surat_id = surat.id')
            ->where('surat.status', 'arsip')
            ->findAll();

        $result = [];
        foreach ($suratQuery as $surat) {
            // Ambil data "kepada"
            $users = $this->select('user.nama, user.nip')
                ->join('user', 'surat_user.user_id = user.id')
                ->where('surat_user.surat_id', $surat['surat_id'])
                ->findAll();

            $kepada = [];
            foreach ($users as $user) {
                $kepada[] = "{$user['nama']} | {$user['nip']}";
            }

            $penandaTangan = $this->db->table('user')
                ->select('nama, nip, jabatan')
                ->where('id', $surat['id_penanda_tangan'])
                ->get()
                ->getRowArray();

            $result[] = [
                'id' => $surat['surat_id'],
                'nomor_surat' => $surat['nomor_surat'],
                'kepada' => implode(' ; ', $kepada),
                'waktu_mulai' => $surat['waktu_mulai'],
                'penanda_tangan' => $penandaTangan ? $penandaTangan['nama'] : null,
                'nip_penanda_tangan' => $penandaTangan ? $penandaTangan['nip'] : null,
                'jabatan_penanda_tangan' => $penandaTangan ? $penandaTangan['jabatan'] : null,
            ];
        }

        return $result;
    }
}
