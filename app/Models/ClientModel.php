<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'client';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['numero', 'nom', 'code'];

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

    public function getClientByNumero($numero)
    {
        return $this->where('numero', $numero)->first();
    }

    // Récupérer tous les clients
    public function getAllClients()
    {
        return $this->findAll();
    }


    // Récupérer un client par son id
    public function getClientById($id)
    {
        return $this->find($id);
    }


    // Situation financière d'un client
    public function getSituationClient($id)
    {
        $data = $this->db->table('historique')
            ->select("
                SUM(
                    CASE 
                        WHEN type_operation.libelle = 'depot'
                        THEN montant
                        ELSE 0
                    END
                ) AS depot,

                SUM(
                    CASE 
                        WHEN type_operation.libelle IN ('transfert','retrait')
                        THEN montant
                        ELSE 0
                    END
                ) AS sortie
            ")
            ->join(
                'type_operation',
                'type_operation.id = historique.id_type_operation'
            )
            ->where('id_client', $id)
            ->get()
            ->getRowArray();
        $data ['solde'] = $data['depot'] - $data['sortie'] ;
        return $data ;
    }

    public function getHistoriqueClient($id)
    {
        return $this->db->table('historique')
            ->select('
                historique.id,
                historique.montant,
                historique.frais,
                historique.date,
                type_operation.libelle AS type_operation
            ')
            ->join(
                'type_operation',
                'type_operation.id = historique.id_type_operation'
            )
            ->where('historique.id_client', $id)
            ->orderBy('historique.date', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getIdClientByNumero($numero)
    {
        $client = $this->where('numero', $numero)
                    ->first();

        if (!$client) {
            return null;
        }

        return $client['id'];
    }

    public function FirstClient()
    {
        return $this->orderBy('id', 'ASC')->first();
    }

}
