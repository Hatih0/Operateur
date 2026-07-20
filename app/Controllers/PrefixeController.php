<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PrefixeModel;

class PrefixeController extends BaseController
{
    protected $prefixeModel;

    public function __construct()
    {
        $this->prefixeModel = new PrefixeModel();
    }

    public function liste()
    {
        $data['prefixes'] = $this->prefixeModel->findAll();
        return view('prefixe/liste', $data);
    }

    public function create()
    {
        return view('prefixe/ajouter');
    }

    public function store()
    {
        $data = [
            'code' => $this->request->getPost('code'),
        ];

        if ($this->prefixeModel->insert($data)) {
            return redirect()->to('/ajouter_prefixe')->with('success', 'Préfixe ajouté avec succès.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'ajout du préfixe.');
        }
    }

    public function edit($id)
    {
        $data['prefixe'] = $this->prefixeModel->find($id);
        return view('prefixe/modifier', $data);
    }

    public function update($id)
    {
        $data = [
            'code' => $this->request->getPost('code'),
        ];

        if ($this->prefixeModel->update($id, $data)) {
            return redirect()->to('/liste_prefixe')->with('success', 'Préfixe mis à jour avec succès.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour du préfixe.');
        }
    }

    public function delete($id)
    {
        if ($this->prefixeModel->delete($id)) {
            return redirect()->to('/liste_prefixe')->with('success', 'Préfixe supprimé avec succès.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression du préfixe.');
        }
    }
}
