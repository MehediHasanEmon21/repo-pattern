<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait{

    public function responseSuccess($data, $message = "Successfull"): JsonResponse
    {
        return response()->json([
            'status' => true,
            'messae' => $message,
            'data' => $data,
            'errors' => null
        ]);
    }

    public function responseError($errors, $message = "Successfull"): JsonResponse
    {
        return response()->json([
            'status' => false,
            'messae' => $message,
            'data' => null,
            'errors' => $errors
        ]);
    }

}