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

    public function validation ($numero) {

        $prefixe = $this->prefixeModel->findAll();
        $prefixes = array_column($prefixe, 'code');

        foreach ($prefixes as $prefix) {
            if (strpos($numero, $prefix) === 0) {
                return true;
            }
        }

        return false;

    }

    public function checkClient() {

        $numero = $this->request->getPost('Numero');
        $Client = $this->clientModel->getClientByNumero($numero);
        if (!$this->validation($numero)) {
            return redirect()->back()->withInput()->with('error', 'Numero de telephone invalide.');
        } else if ($Client) {

            session()->set('client_id', $Client['id']);
            session()->set('ClientLoggedIn', true);
            return redirect()->to('/client/situation/'.$Client['id'])->with('success', 'Connexion réussie.');

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
