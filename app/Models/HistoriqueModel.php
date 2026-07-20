<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueModel extends Model
{
    protected $table = 'historique';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'id_client',
        'id_destinataire',
        'id_type_operation',
        'montant',
        'date',
        'frais',
        'commission'
    ];


    /**
     * Vérifie que le solde du client est suffisant pour couvrir le montant saisi.
     * Règle métier : solde - montantSaisie >= 0
     *
     * @return bool true si l'opération est autorisée, false si le solde est insuffisant
     */
    public function soldeSuffisant($id_client, $montantsaisie)
    {
        $clientModel = new \App\Models\ClientModel();

        $solde = $clientModel->getSoldeClient($id_client);

        return ($solde - $montantsaisie) >= 0;
    }



    /**
     * Récupération des frais selon le montant
     */
    public function getFrais($id_type_operation, $montant)
    {
        $configuration = $this->db->table('configuration')
            ->where('id_type_operation', $id_type_operation)
            ->where('min <=', $montant)
            ->where('max >=', $montant)
            ->get()
            ->getRowArray();


        if (!$configuration) {
            return 0;
        }


        return $configuration['montant'];
    }



    /**
     * Dépôt
     */
    public function depot($id_client, $montantsaisie, $id_type_operation)
    {
        $frais = $this->getFrais(
            $id_type_operation,
            $montantsaisie
        );


        $montant = $montantsaisie;


        return $this->insert([
            'id_client' => $id_client,
            'id_destinataire' => null,
            'id_type_operation' => $id_type_operation,
            'montant' => $montant,
            'frais' => $frais
        ]);
    }

        /**
     * recus
     */

    public function recus($id_client, $montantsaisie, $id_type_operation){

        $frais = 0;
        $depot_id = 1;

        return $this->insert([
            'id_client' => $id_client,
            'id_destinataire' => null,
            'id_type_operation' => $depot_id,
            'montant' => $montantsaisie,
            'frais' => $frais
        ]);

    }

    /**
     * Retrait
     */
    public function retrait($id_client, $montantsaisie, $id_type_operation)
    {
        $frais = $this->getFrais(
            $id_type_operation,
            $montantsaisie
        );


        $montant = $montantsaisie;


        return $this->insert([
            'id_client' => $id_client,
            'id_destinataire' => null,
            'id_type_operation' => $id_type_operation,
            'montant' => $montant,
            'frais' => $frais
        ]);
    }




    /**
     * Transfert
     */
    public function transfert(
        $id_client,
        $id_destinataire,
        $montantsaisie,
        $id_type_operation
    )
    {

        $frais = $this->getFrais(
            $id_type_operation,
            $montantsaisie
        );


        $montant = $montantsaisie;


        return $this->insert([
            'id_client' => $id_client,

            'id_destinataire' => $id_destinataire,

            'id_type_operation' => $id_type_operation,

            'montant' => $montant,

            'frais' => $frais
        ]);
    }

}
