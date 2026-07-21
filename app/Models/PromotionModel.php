<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $table = 'promotion';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'pourcentage'
    ];


    public function getPromotion () {
        $promotion = $this->findAll();
        return $promotion [0]['pourcentage'] ;
    }
}
