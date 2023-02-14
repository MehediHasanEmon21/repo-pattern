<?php

namespace App\Interfaces;

use App\Models\Product;

interface DBPreparableInterface{
    
    public function prepareForDB(array $data): array;


}