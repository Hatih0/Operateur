<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'type_operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['libelle'];

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

public function getGainParType($id_type_operation, $date, $isAutreOperateur)
{

    if ($isAutreOperateur) {
        return $this->db->table('historique h')
            ->select('COUNT(*) AS nombre, SUM(h.frais) AS gain')
            ->join('client c', 'c.id = h.id_destinataire')
            ->where('h.id_type_operation', $id_type_operation)
            ->where('c.operateur_id !=', 1)
            ->where('h.date <=', $date)
            ->get()
            ->getRowArray();
    } else {
        return $this->db->table('historique h')
            ->select('COUNT(*) AS nombre, SUM(h.frais) AS gain')
            ->join('client c', 'c.id = h.id_client')
            ->where('h.id_type_operation', $id_type_operation)
            ->where('c.operateur_id', 1)
            ->where('h.date <=', $date)
            ->get()
            ->getRowArray();
    }
}


    public function getGainTotal($date,$isAutreOperateur)
    {
        $types = $this->findAll();

        $totalNombre = 0;
        $totalGain = 0;

        foreach ($types as $type) {
            $gain = $this->getGainParType($type['id'], $date, $isAutreOperateur);

            $totalNombre += $gain['nombre'] ?? 0;
            $totalGain += $gain['gain'] ?? 0;
        }

        return [
            'nombre' => $totalNombre,
            'gain'   => $totalGain
        ];
    }



}
