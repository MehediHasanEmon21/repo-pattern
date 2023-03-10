<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateReuest;
use App\Repositories\ProductRepository;
use Exception;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    use ResponseTrait;

    public $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

 /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get All Prroducts",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="index",
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Status values that needed to be considered for filter",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="10",
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            return $this->responseSuccess($this->productRepository->getAll(request()->all()), 'Successfully Done');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(), $exception->getCode());
        }
    }

    public function store(ProductCreateRequest $request)
    {
        try {
            return $this->responseSuccess($this->productRepository->create($request->validated()), 'Successfully Product Created');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(), $exception->getCode());
        }
    }

    public function update(ProductUpdateReuest $request, $id)
    {
        try {
            return $this->responseSuccess($this->productRepository->update($request->validated(), $id), 'Successfully Product Updated');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(), $exception->getCode());
        }
    }

    
    public function destroy($id)
    {
        try {
            return $this->responseSuccess($this->productRepository->delete($id), 'Successfully Product Deleted');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(), $exception->getCode());
        }
    }
}
