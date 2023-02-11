<?php

namespace App\Http\Controllers;


use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Repositories\AuthRepositoy;
use App\Traits\ResponseTrait;
use Exception;


class RegisterController extends Controller
{

    use ResponseTrait;

    public function __construct(private AuthRepositoy $auth)
    {
        $this->auth = $auth;
    }

    public function register(RegisterRequest $request)
    {

        try {
            $data = $this->auth->register($request->validated());
            return $this->responseSuccess($data, 'User Register Successfully');
        } catch (Exception $e) {
            return $this->responseError([], $e->getMessage());
        }
    }
}
