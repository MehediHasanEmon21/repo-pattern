<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Interfaces\CrudInterface;
use App\Interfaces\DBPreparableInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class ProductRepository implements CrudInterface, DBPreparableInterface{

    public function getAll(array $filterData): Paginator
    {   
        $filter = $this->getFilterData($filterData);

        $query = Product::orderBy($filter['orderBy'],$filter['order']);

        if(!empty($filter['search'])){
            $query->where(function($q) use ($filter) {
                $searchData = '%'. $filter['search'] . '%';
                $q->where('title', 'LIKE', $searchData)
                  ->orWhere('slug', 'LIKE', $searchData);
            });
        }
        return $query->paginate($filter['perPage']);
    }

    public function getFilterData(array $filterData): array
    {

        $defaultArs = [
            'perPage' => 10,
            'search' => '',
            'orderBy' => 'id',
            'order' => 'desc'
        ];

        return array_merge($defaultArs, $filterData);
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): ?Product
    {   
        $data = $this->prepareForDB($data);
        return Product::create($data);
    }

    public function prepareForDB(array $data): array
    {

        $data['slug'] = $this->createUniqueSlug($data);
        $data['user_id'] = Auth::user()->id;
        return $data;

    }

    public function createUniqueSlug(array $data): string
    {
        $slug = Product::where('slug', Str::slug($data['title']))->exists();

        if($slug){
            return Str::slug($data['title']). '-' . time();
        }
        return Str::slug($data['title']);

    }

}