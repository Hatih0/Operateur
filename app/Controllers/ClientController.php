<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ConfigurationModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{

    protected $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

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
            'operation' => $operation,
            'solde' => $clientModel->getSoldeClient($id)
        ];


        return view('Client/formulaire', $data);
    }

    public function insertionMultiple()
    {
        $id = session()->get('client_id');

        $clientModel = new ClientModel();

        $client = $clientModel->getClientById($id);

        if (!$client) {
            return redirect()->back()
                ->with('error', 'Client introuvable');
        }

        $data = [
            'client' => $client,
            'id_type_operation' => 3,
            'solde' => $clientModel->getSoldeClient($id)
        ];

        return view('Client/insertionmultiple', $data);
    }

    /**
     * Renvoie en JSON la configuration (frais) correspondant
     * au type d'opération et au montant saisi par le client.
     */
    public function getFrais()
    {
        $id_type_operation = $this->request->getGet('id_type_operation');
        $montant = $this->request->getGet('montant');

        if ($id_type_operation === null || $montant === null || $montant === '') {
            return $this->response->setJSON(['found' => false]);
        }

        $configurationModel = new ConfigurationModel();

        $configuration = $configurationModel
            ->where('id_type_operation', $id_type_operation)
            ->where('min <=', $montant)
            ->where('max >=', $montant)
            ->first();

        if (!$configuration) {
            return $this->response->setJSON(['found' => false]);
        }

        return $this->response->setJSON([
            'found' => true,
            'montant' => $configuration['montant'],
        ]);
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
                $meme_operateur = $this->request->getPost('meme_operateur') === '1';
                $inclure_frais_retrait = $this->request->getPost('inclure_frais') === '1';


                $clientModel = new \App\Models\ClientModel();

                $destinataire = $clientModel
                    ->where('numero', $numero_destinataire)
                    ->first();


                if (!$destinataire) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Destinataire introuvable');
                }


                $fraisTransfert = $historiqueModel->getFrais($id_type_operation, $montant);

                // Frais que destinataire sera chargee de payer
                $fraisRetraitDestinataire = $historiqueModel->getFrais(2, $montant);


                $inclureFraisRetraitEffectif = $meme_operateur && $inclure_frais_retrait;

                $calcul = $historiqueModel->calculerTransfert(
                    $montant,
                    $fraisTransfert,
                    $fraisRetraitDestinataire,
                    $inclureFraisRetraitEffectif
                );


                if (!$historiqueModel->soldeSuffisant($id_client, $calcul['total'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Solde insuffisant pour effectuer ce transfert.');
                }

                $operateurExpediteur = $this->clientModel->get_operateur($id_client);
                $operateurDestinataire = $this->clientModel->get_operateur($destinataire['id']);

                $isAutreOperateur = false;

                if (
                    $operateurExpediteur
                    && $operateurDestinataire
                    && $operateurExpediteur['operateur_id'] !== $operateurDestinataire['operateur_id']
                ) {
                    $isAutreOperateur = true;
                }

                $historiqueModel->transfert(
                    $id_client,
                    $destinataire['id'],
                    $calcul['montant'],
                    $id_type_operation,
                    $isAutreOperateur
                );

                $historiqueModel->recus(
                    $destinataire['id'],
                    $calcul['montant'],
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

    public function operationMultiple()
    {
        $id_client = $this->request->getPost('id_client');

        if (empty($id_client)) {
            $clientModel = new ClientModel();
            $id_client = $clientModel->getIdClientByNumero($this->request->getPost('numero'));
        }

        $montantTotal = (float) $this->request->getPost('montant');
        $inclure_frais_retrait = $this->request->getPost('inclure_frais') === '1';

        $numeros = $this->request->getPost('numero_destinataire');
        $numeros = is_array($numeros) ? $numeros : [];

        // On ne garde que les numéros non vides
        $numeros = array_values(array_filter($numeros, function ($numero) {
            return trim((string) $numero) !== '';
        }));

        if (empty($numeros)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Veuillez ajouter au moins un destinataire.');
        }

        if (!$montantTotal || $montantTotal <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Montant invalide.');
        }

        $nombreDestinataires = count($numeros);

        // Division équitable du montant total entre tous les destinataires
        $montantParDestinataire = $montantTotal / $nombreDestinataires;

        $id_type_operation = 3; // transfert

        $historiqueModel = new \App\Models\HistoriqueModel();
        $clientModel = new \App\Models\ClientModel();

        $destinataires = [];

        foreach ($numeros as $numero) {
            $destinataire = $clientModel
                ->where('numero', $numero)
                ->first();

            if (!$destinataire) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Destinataire introuvable pour le numéro : ' . $numero);
            }

            $destinataires[] = $destinataire;
        }

        // Le montant étant divisé équitablement, le frais de transfert et le
        // frais de retrait du destinataire sont identiques pour chaque envoi.
        $fraisTransfert = $historiqueModel->getFrais($id_type_operation, $montantParDestinataire);
        $fraisRetraitDestinataire = $historiqueModel->getFrais(2, $montantParDestinataire);

        $calcul = $historiqueModel->calculerTransfert(
            $montantParDestinataire,
            $fraisTransfert,
            $fraisRetraitDestinataire,
            $inclure_frais_retrait
        );

        $totalPreleve = $calcul['total'] * $nombreDestinataires;

        if (!$historiqueModel->soldeSuffisant($id_client, $totalPreleve)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Solde insuffisant pour effectuer ces transferts.');
        }

        foreach ($destinataires as $destinataire) {
            $historiqueModel->transfert(
                $id_client,
                $destinataire['id'],
                $calcul['montant'],
                $calcul['frais'],
                $id_type_operation
            );

            $historiqueModel->recus(
                $destinataire['id'],
                $calcul['montant'],
                $id_type_operation
            );
        }

        return redirect()
            ->to('/client/situation')
            ->with('success', 'Insertion multiple effectuée avec succès (' . $nombreDestinataires . ' destinataires).');
    }
}
