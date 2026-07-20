<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TypeOperationModel;

class TypeOperationController extends BaseController
{
    protected $typeOperationModel;
    public function __construct()
    {
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        //
    }

    public function liste()
    {
        $data['typeOperations'] = $this->typeOperationModel->findAll();
        return view('typeOperation/liste', $data);
    }

    public function create()
    {
        return view('typeOperation/ajouter');
    }

    public function store()
    {
        $data = [
            'libelle' => $this->request->getPost('libelle')
        ];
        $this->typeOperationModel->save($data);
        return redirect()->to('/liste_type_operation');
    }

    public function edit($id)
    {
        $data['typeOperation'] = $this->typeOperationModel->find($id);
        return view('typeOperation/modifier', $data);
    }

    public function update($id)
    {
        $data = [
            'libelle' => $this->request->getPost('libelle')
        ];
        $this->typeOperationModel->update($id, $data);
        return redirect()->to('/liste_type_operation');
    }

    public function delete($id)
    {
        $this->typeOperationModel->delete($id);
        return redirect()->to('/liste_type_operation');
    }

}
