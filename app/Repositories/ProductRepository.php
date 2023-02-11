<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;

class ProductRepository implements CrudInterface{

    public function getAll(?int $perPae = 10): Paginator
    {
        return Product::paginate(10);
    }

    public function findById(int $id): Product
    {
        return Product::find($id);
    }

}