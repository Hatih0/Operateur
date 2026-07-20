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
            'frais' => $frais,
            'commission' => 0
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
            'frais' => $frais,
            'commission' => 0
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
            'frais' => $frais,
            'commission' => 0
        ]);
    }




    /**
     * Transfert
     *
     * $montant, $frais et $commission sont déjà calculés par l'appelant
     * (ClientController) :
     * - $montant : montant effectivement envoyé/stocké pour le destinataire
     *   (issu de calculerTransfert(), tient compte du choix "inclure le
     *   frais de retrait du destinataire")
     * - $frais : frais du transfert lui-même
     * - $commission : commission prélevée en plus si le transfert se fait
     *   vers un autre opérateur (0 sinon)
     */
    public function transfert(
        $id_client,
        $id_destinataire,
<<<<<<< HEAD
        $montant,
        $frais,
        $commission,
        $id_type_operation
=======
        $montantsaisie,
        $id_type_operation,
        $isAutreOperateur
>>>>>>> 1c428d50af3f31c4a0815e6dcc2c28fea6554f73
    )
    {

        $frais = $this->getFrais(
            $id_type_operation,
            $montantsaisie
        );


        $montant = $montantsaisie;
        $commission = 0;

        if ($isAutreOperateur) {
            $commission = $montant * 0.1;
            $frais = 0;
        }

        return $this->insert([
            'id_client' => $id_client,

            'id_destinataire' => $id_destinataire,

            'id_type_operation' => $id_type_operation,

            'montant' => $montant,

            'frais' => $frais,

            'commission' => $commission

        ]);
    }

    /**
     * Taux de commission appliqué lorsque le transfert se fait vers un
     * autre opérateur (10 %).
     */
    public const TAUX_COMMISSION_AUTRE_OPERATEUR = 0.1;

    /**
     * Détermine le montant à envoyer/stocker pour le destinataire, le frais
     * de transfert, la commission (si transfert vers un autre opérateur)
     * et le total à prélever sur le solde de l'expéditeur.
     *
     * Règle métier :
     * - le frais de retrait du destinataire ($fraisRetrait) n'est ajouté
     *   au montant envoyé que si le client choisit de l'inclure
     *   ($inclureFraisRetrait = true), et uniquement pertinent pour un
     *   transfert vers le même opérateur.
     * - la commission ($isAutreOperateur = true) s'applique en plus,
     *   qu'elle que soit le choix "inclure frais de retrait" (elle ne
     *   concerne que les transferts inter-opérateurs, pour lesquels le
     *   frais de retrait destinataire n'a pas de sens puisqu'il s'agit
     *   d'un autre réseau).
     * - total prélevé = montant envoyé + frais de transfert + commission
     */
    public function calculerTransfert(
        $montantsaisie,
        $fraisTransfert,
        $fraisRetrait,
        bool $inclureFraisRetrait,
        bool $isAutreOperateur = false
    ) {
        $montant = $inclureFraisRetrait
            ? ($montantsaisie + $fraisRetrait)
            : $montantsaisie;

        $commission = $isAutreOperateur
            ? round($montant * self::TAUX_COMMISSION_AUTRE_OPERATEUR, 2)
            : 0;

        return [
            'montant'    => $montant,
            'frais'      => $fraisTransfert,
            'commission' => $commission,
            'total'      => $montant + $fraisTransfert + $commission,
        ];

    }
    
    public function getTotalGainsByOperateur($id_operateur)
    {
        return $this->db->table('historique h')
            ->select('COUNT(*) AS nombre,SUM(h.frais) AS total_gains')
            ->join('client c', 'c.id = h.id_client')
            ->where('c.operateur_id', $id_operateur)
            ->get()
            ->getRowArray();
    }

    public function getTotalGainsByOperateur($id_operateur)
    {
        return $this->db->table('historique h')
            ->select('COUNT(*) AS nombre,SUM(h.frais) AS total_gains')
            ->join('client c', 'c.id = h.id_client')
            ->where('c.operateur_id', $id_operateur)
            ->get()
            ->getRowArray();
    }

}
