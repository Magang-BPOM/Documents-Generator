<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['nip', 'nama', 'jabatan', 'pangkat', 'role'];

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
    // Aturan validasi
    protected $validationRules = [
        'nama'        => 'required|min_length[3]|max_length[255]',
        'nip'         => 'required|min_length[8]|max_length[100]|is_unique[user.nip]',
        'jabatan'     => 'required|min_length[3]|max_length[100]',
        'pangkat'     => 'permit_empty|max_length[100]',
        'foto_profil' => 'permit_empty|valid_image[foto_profil]',
        'password'    => 'required|min_length[6]|max_length[255]',
        'role'        => 'required|in_list[admin,pegawai]',
    ];
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

    public function user()
    {
        return $this->findAll(); // Sesuaikan query jika perlu
    }
}
