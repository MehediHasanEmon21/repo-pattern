<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Interfaces\CrudInterface;
use App\Interfaces\DBPreparableInterface;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductRepository implements CrudInterface, DBPreparableInterface
{

    public function getAll(array $filterData): Paginator
    {
        $filter = $this->getFilterData($filterData);
        $query = Product::orderBy($filter['orderBy'], $filter['order']);

        if (!empty($filter['search'])) {
            $query->where(function ($q) use ($filter) {
                $searchData = '%' . $filter['search'] . '%';
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
        $product = Product::find($id);

        if (empty($product)) {
            throw new Exception("Product does not exist.", Response::HTTP_NOT_FOUND);
        }

        return $product;
    }

    public function create(array $data): ?Product
    {
        $data = $this->prepareForDB($data);
        return Product::create($data);
    }

    public function update(array $data, int $id): ?Product
    {
        $product = $this->findById($id);
        $data = $this->prepareForDB($data, $product);
        $updated = $product->update($data);

        if ($updated) {
            $product = $this->findById($id);
        }

        return $product;
    }

    public function delete(int $id): ?Product
    {

        $product = $this->findById($id);
        $this->deleteImage($product->image_url);
        $deleted = $product->delete();

        if (!$deleted) {
            throw new Exception("Product could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $product;
    }


    public function prepareForDB(array $data, ?Product $product = null): array
    {

        $data['slug'] = $this->createUniqueSlug($data);
        $data['user_id'] = Auth::user()->id;

        if (!empty($data['image'])) {
            
            if (!is_null($product)) {
                $this->deleteImage($product->image_url);
            }

            $data['image'] = $this->uploadImage($data['image']);
        }
        
        return $data;
    }

    public function createUniqueSlug(array $data): string
    {
        $slug = Product::where('slug', Str::slug($data['title']))->exists();

        if ($slug) {
            return Str::slug($data['title']) . '-' . time();
        }

        return Str::slug($data['title']);
    }

    public function uploadImage($image): string
    {
        $imageName = time() . '.' . $image->extension();
        $image->storePubliclyAs('public/product', $imageName);
        return $imageName;
    }

    public function deleteImage($imageUrl): void
    {
        if (!empty($imageUrl)) {
            $imageName = ltrim(strstr($imageUrl, 'storage/product/'), 'storage/product/');

            if (!empty($imageName) && Storage::exists('public/product/' . $imageName)) {
                Storage::delete('public/product/' . $imageName);
            }

        }
    }
}
