<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TypeOperationModel;
use CodeIgniter\HTTP\ResponseInterface;

class OperateurController extends BaseController
{
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
        $total = $typeOperationModel->getGainTotal($date);


        // Gains par type d'opération
        $types = $typeOperationModel->findAll();

        $situation = [];

        foreach ($types as $type) {

            $gain = $typeOperationModel->getGainParType(
                $type['id'],
                $date
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
}