<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ConfigurationModel;
use App\Models\TypeOperationModel;

class ConfigurationController extends BaseController
{
    protected $configurationModel;
    protected $typeOperationModel;

    public function __construct()
    {
        $this->typeOperationModel = new TypeOperationModel();
        $this->configurationModel = new ConfigurationModel();
    }

    public function liste()
    {
        $data['configurations'] = $this->configurationModel->findAll();

        return view('configuration/liste', $data);
    }

    public function create()
    {
        $data['typeOperations'] = $this->typeOperationModel->findAll();
        return view('configuration/ajouter', $data);
    }

    public function store()
    {
        $typeOperation_id = $this->request->getPost('typeOperation_id');
        $min = $this->request->getPost('min');
        $max = $this->request->getPost('max');
        $montant = $this->request->getPost('montant');

        $data = [
            'id_type_operation' => $typeOperation_id,
            'min' => $min,
            'max' => $max,
            'montant' => $montant,
        ];
        
        if ($this->configurationModel->insert($data)) {
            return redirect()->to('/ajouter_configuration')->with('success', 'Configuration ajoutée avec succès.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'ajout de la configuration.');
        }
        
    }

    public function edit($id)
    {
        $data['configuration'] = $this->configurationModel->find($id);
        $data['typeOperations'] = $this->typeOperationModel->findAll();
        return view('configuration/modifier', $data);
    }

    public function update($id)
    {
        $typeOperation_id = $this->request->getPost('typeOperation_id');
        $min = $this->request->getPost('min');
        $max = $this->request->getPost('max');
        $montant = $this->request->getPost('montant');

        $data = [
            'id_type_operation' => $typeOperation_id,
            'min' => $min,
            'max' => $max,
            'montant' => $montant,
        ];

        if ($this->configurationModel->update($id, $data)) {
            return redirect()->to('/liste_configuration')->with('success', 'Configuration mise à jour avec succès.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour de la configuration.');
        }
    }

    public function delete($id)
    {
        if ($this->configurationModel->delete($id)) {
            return redirect()->to('/liste_configuration')->with('success', 'Configuration supprimée avec succès.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression de la configuration.');
        }
    }

}
