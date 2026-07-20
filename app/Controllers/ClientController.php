<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{
    public function situation($id)
    {
        $clientModel = new ClientModel();

        $data = [
            'informations' => $clientModel->getClientById($id),

            'situation' => $clientModel->getSituationClient($id),

            'historique' => $clientModel->getHistoriqueClient($id)
        ];

        return view('client/situation', $data);
    }
}