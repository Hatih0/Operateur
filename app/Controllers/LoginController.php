<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PrefixeModel;
use App\Models\ClientModel;
use App\Models\OperateurModel;

class LoginController extends BaseController
{
    protected $prefixeModel;
    protected $clientModel;
    protected $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
        $this->prefixeModel = new PrefixeModel();
        $this->clientModel = new ClientModel();
    }

    public function index()
    {
        $FirstClient = $this->clientModel->FirstClient();
        return view('Login/login_client', ['FirstClient' => $FirstClient]);
    }

    /**
     * Recherche le préfixe correspondant au début du numéro donné.
     * Renvoie null si aucun préfixe enregistré ne correspond.
     */
    private function findPrefixeForNumero($numero)
    {
        $prefixes = $this->prefixeModel->findAll();

        foreach ($prefixes as $prefixe) {
            if (strpos($numero, $prefixe['code']) === 0) {
                return $prefixe;
            }
        }

        return null;
    }

    /**
     * Un numéro n'est valide pour la connexion client que si son préfixe
     * appartient à l'opérateur principal (id = 1). Les numéros des autres
     * opérateurs (Telma, Orange, ...) ne peuvent pas se connecter ici.
     */
    public function validation ($numero) {

        $prefixe = $this->findPrefixeForNumero($numero);

        return $prefixe !== null && (int) $prefixe['operateur_id'] === 1;

    }

    public function checkClient() {

        $numero = $this->request->getPost('Numero');
        $Client = $this->clientModel->getClientByNumero($numero);
        $prefixe = $this->findPrefixeForNumero($numero);

        if ($prefixe === null) {
            return redirect()->back()->withInput()->with('error', 'Numero de telephone invalide.');
        } else if ((int) $prefixe['operateur_id'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'Ce numéro appartient à un autre opérateur, la connexion est réservée aux clients de notre réseau.');
        } else if ($Client) {

            session()->set('client_id', $Client['id']);
            session()->set('ClientLoggedIn', true);
            return redirect()->to('/client/situation')->with('success', 'Connexion réussie.');

        } else {
            return redirect()->back()->withInput()->with('error', 'Personne n a ce numero de telephone.');
        }

    }

    public function LoginOperateur() {
        $FirstOperateur = $this->operateurModel->FirstOperateur();
        return view('Login/login_operateur', ['FirstOperateur' => $FirstOperateur]);
    }

    public function checkOperateur() {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');


        $operateur = $this->operateurModel->CheckOperateur($username, $password);

        if ($operateur) {
            session()->set('operateur_id', $operateur['id']);
            session()->set('OperateurLoggedIn', true);
            return redirect()->to('/liste_configuration')->with('success', 'Connexion réussie.');
        } else {
            return redirect()->to('/login_operateur')->with('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
        }
    }

    public function logout() {
        $isOperateur = session()->get('OperateurLoggedIn');

        session()->destroy();

        return redirect()->to($isOperateur ? '/login_operateur' : '/login_client')
            ->with('success', 'Vous avez été déconnecté.');
    }

}
