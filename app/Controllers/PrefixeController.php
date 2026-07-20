<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PrefixeModel;
use App\Models\OperateurModel;

class PrefixeController extends BaseController
{
    protected $prefixeModel;
    protected $operateurModel;

    public function __construct()
    {
        $this->prefixeModel = new PrefixeModel();
        $this->operateurModel = new OperateurModel();
    }

    public function liste()
    {
        $data['prefixes'] = $this->prefixeModel
            ->select('prefixe.*, operateur.nom AS operateur_nom')
            ->join('operateur', 'operateur.id = prefixe.operateur_id')
            ->findAll();

        return view('prefixe/liste', $data);
    }

    public function create()
    {
        $data['operateurs'] = $this->operateurModel->findAll();

        return view('prefixe/ajouter', $data);
    }

    public function store()
    {
        $data = [
            'code' => $this->request->getPost('code'),
            'operateur_id' => $this->request->getPost('operateur_id'),
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
        $data['operateurs'] = $this->operateurModel->findAll();

        return view('prefixe/modifier', $data);
    }

    public function update($id)
    {
        $data = [
            'code' => $this->request->getPost('code'),
            'operateur_id' => $this->request->getPost('operateur_id'),
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
