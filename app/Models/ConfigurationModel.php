<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigurationModel extends Model
{
    protected $table            = 'configuration';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_type_operation', 'min', 'max', 'montant'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
    'min' => 'required|numeric|less_than_equal_to[max]',
    'max' => 'required|numeric'
    ];
    
    protected $validationMessages   = [
        'min' => [
            'less_than' => 'Le champ "min" doit être inférieur ou égal à "max".'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

}
