<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductImage extends Model
{

    protected $table = 'tbl_productimages';
    protected $primaryKey = 'productimages_id';

    protected $allowedFields = [
        'product_image',
        'product_id',
        'added_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'is_deleted';

    public function newImage(array $details)
    {
        $this->insert($details);
    }
}
