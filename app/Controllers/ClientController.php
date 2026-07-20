<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{
    public function index()
    {
        return view('Client/index');
    }

    public function situation()
    {
        $id = session()->get('client_id');

        $clientModel = new ClientModel();

        $data = [
            'informations' => $clientModel->getClientById($id),

            'situation' => $clientModel->getSituationClient($id),

            'historique' => $clientModel->getHistoriqueClient($id)
        ];

        return view('Client/situation', $data);
    }

    public function formulaire($operation)
    {
        $id = session()->get('client_id');

        $clientModel = new ClientModel();


        $client = $clientModel->getClientById($id);


        if (!$client) {
            return redirect()->back()
                ->with('error', 'Client introuvable');
        }


        $transfert = false;


        switch ($operation) {

            case 'depot':
                $id_type_operation = 1;
                break;


            case 'retrait':
                $id_type_operation = 2;
                break;


            case 'transfert':
                $id_type_operation = 3;
                $transfert = true;
                break;


            default:
                return redirect()->back()
                    ->with('error', 'Type opération invalide');
        }


        $data = [
            'client' => $client,
            'id_type_operation' => $id_type_operation,
            'transfert' => $transfert,
            'operation' => $operation
        ];


        return view('Client/formulaire', $data);
    }

    public function operation()
    {
        $id_client = $this->request->getPost('id_client') ;

        if (empty($id_client)) {
            $clientModel = new ClientModel () ;
            $id_client = $clientModel->getIdClientByNumero($this->request->getPost('numero'));
        }
        $id_type_operation = $this->request->getPost('id_type_operation');
        $montant = $this->request->getPost('montant');

        $historiqueModel = new \App\Models\HistoriqueModel();


        switch ($id_type_operation) {

            // Dépôt
            case 1:

                $historiqueModel->depot(
                    $id_client,
                    $montant,
                    $id_type_operation
                );

                break;


            // Retrait
            case 2:

                if (!$historiqueModel->soldeSuffisant($id_client, $montant)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Solde insuffisant pour effectuer ce retrait.');
                }

                $historiqueModel->retrait(
                    $id_client,
                    $montant,
                    $id_type_operation
                );

                break;


            // Transfert
            case 3:

                $numero_destinataire = $this->request->getPost('numero_destinataire');


                $clientModel = new \App\Models\ClientModel();

                $destinataire = $clientModel
                    ->where('numero', $numero_destinataire)
                    ->first();


                if (!$destinataire) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Destinataire introuvable');
                }


                if (!$historiqueModel->soldeSuffisant($id_client, $montant)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Solde insuffisant pour effectuer ce transfert.');
                }


                $historiqueModel->transfert(
                    $id_client,
                    $destinataire['id'],
                    $montant,
                    $id_type_operation
                );

                break;


            default:

                return redirect()->back()
                    ->with('error', 'Type opération invalide');
        }


        return redirect()
            ->to('/client/situation')
            ->with('success', 'Opération effectuée');
    }
}
