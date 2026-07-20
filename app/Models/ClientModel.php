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
    protected $allowedFields    = ['numero', 'nom', 'code', 'operateur_id'];

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
                        THEN montant + frais + commission
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

        // NB : les transferts reçus sont déjà comptabilisés ci-dessus via
        // la ligne "depot" créée par HistoriqueModel::recus() pour le
        // destinataire. La colonne "id_destinataire" ne concerne que la
        // ligne de l'expéditeur (pour savoir à qui il a envoyé l'argent) et
        // ne doit donc pas être resommée ici, sous peine de compter deux
        // fois le même transfert (avec en plus le mauvais signe).
        $data['solde'] = ($data['depot'] ?? 0) - ($data['sortie'] ?? 0);

        return $data ;
    }

    /**
     * Retourne uniquement le solde actuel d'un client
     * (dépôts - sorties (retraits/transferts envoyés) + transferts reçus)
     */
    public function getSoldeClient($id)
    {
        $situation = $this->getSituationClient($id);

        return $situation['solde'] ?? 0;
    }

    public function getHistoriqueClient($id)
    {
        return $this->db->table('historique')
            ->select('
                historique.id,
                historique.montant,
                historique.frais,
                historique.date,
                type_operation.libelle AS type_operation,
                historique.commission
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

    public function get_operateur ($id_client) {

        return $this->db->table('client')
            ->select('operateur_id')
            ->where('client.id', $id_client)
            ->get()
            ->getRowArray();
    }

<<<<<<< HEAD
    /**
     * Détermine si deux clients appartiennent à des opérateurs différents
     * (utilisé pour savoir si une commission de transfert doit s'appliquer).
     */
    public function isAutreOperateur($id_client, $id_destinataire)
    {
        $operateurExpediteur = $this->get_operateur($id_client);
        $operateurDestinataire = $this->get_operateur($id_destinataire);

        if (!$operateurExpediteur || !$operateurDestinataire) {
            return false;
        }

        return $operateurExpediteur['operateur_id'] !== $operateurDestinataire['operateur_id'];
    }

=======
>>>>>>> 1c428d50af3f31c4a0815e6dcc2c28fea6554f73
}
