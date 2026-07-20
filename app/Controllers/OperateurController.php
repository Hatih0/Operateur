<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TypeOperationModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\OperateurModel;
use App\Models\HistoriqueModel;

class OperateurController extends BaseController
{
    protected $operateurModel;
    protected $historiqueModel;

    public function __construct()
    {
        $this->historiqueModel = new HistoriqueModel();
        $this->operateurModel = new OperateurModel();
    }

    public function getSituationGain()
    {
        $typeOperationModel = new TypeOperationModel();

        // Récupération de la date envoyée par POST
        $date = $this->request->getPost('date');

        // Si aucune date n'est envoyée, prendre la date actuelle
        if (!$date) {
            $date = date('Y-m-d H:i:s');
        }

        // Gain total et nombre total d'opérations
        $total = $typeOperationModel->getGainTotal($date,false);


        // Gains par type d'opération
        $types = $typeOperationModel->findAll();

        $situation = [];

        foreach ($types as $type) {

            $gain = $typeOperationModel->getGainParType(
                $type['id'],
                $date,
                false
            );

            $situation[$type['libelle']] = [
                'nombre' => $gain['nombre'] ?? 0,
                'gain'   => $gain['gain'] ?? 0
            ];
        }


        $data = [
            'date' => $date,

            // Tous les gains
            'totalGain' => $total['gain'],
            'totalOperation' => $total['nombre'],

            // Détails par type
            'situation' => $situation
        ];


        return view('operateur/gain', $data);
    }

        public function getSituationGainAutreOperateur()
    {
        $typeOperationModel = new TypeOperationModel();

        // Récupération de la date envoyée par POST
        $date = $this->request->getPost('date');

        // Si aucune date n'est envoyée, prendre la date actuelle
        if (!$date) {
            $date = date('Y-m-d H:i:s');
        }

        // Gain total et nombre total d'opérations
        $total = $typeOperationModel->getGainTotal($date,true);


        // Gains par type d'opération
        $types = $typeOperationModel->findAll();

        $situation = [];

        foreach ($types as $type) {

            $gain = $typeOperationModel->getGainParType(
                $type['id'],
                $date,
                true
            );

            $situation[$type['libelle']] = [
                'nombre' => $gain['nombre'] ?? 0,
                'gain'   => $gain['gain'] ?? 0
            ];
        }


        $data = [
            'date' => $date,

            // Tous les gains
            'totalGain' => $total['gain'],
            'totalOperation' => $total['nombre'],

            // Détails par type
            'situation' => $situation
        ];


        return view('operateur/gain', $data);
    }


    public function situationClient($id)
    {
        $clientModel = new ClientModel();

        $data = [
            'client' => $clientModel->getClientById($id),
            'situation' => $clientModel->getSituationClient($id),
            'historique' => $clientModel->getHistoriqueClient($id)
        ];

        return view('operateur/situationClient', $data);
    }

    public function getAllClients() {
        $clientModel = new ClientModel ();
        return view ('operateur/clients',['clients' => $clientModel->findAll()]);
    }

    public function listeOperateurs() {

        $operateurs = $this->operateurModel->findAll();

        return view('operateur/listeOperateur', ['operateurs' => $operateurs]);
    }

    public function situationAutreOperateur($id_operateur)
    {
        // montant total des gains pour l'opérateur spécifié
        $totalGains = $this->historiqueModel->getTotalGainsByOperateur($id_operateur);
        $operateur = $this->operateurModel->find($id_operateur);

        return view('operateur/situationChaqueOperateur', [
            'totalGains' => $totalGains['total_gains'] ?? 0,
            'totalOperation' => $totalGains['nombre'] ?? 0,
            'operateur' => $operateur['nom'] 
        ]);

    }

}