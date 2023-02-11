<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Repositories\AuthRepositoy;
use App\Traits\ResponseTrait;
use Exception;


class LoginController extends Controller
{

    use ResponseTrait;

    public function __construct(private AuthRepositoy $auth)
    {
        $this->auth = $auth;
    }

    public function login(LoginRequest $request)
    {

        try {

            $data = $this->auth->login($request->validated());
            return $this->responseSuccess($data, 'Logged in Successfully');
        } catch (Exception $e) {

            return $this->responseError([], $e->getMessage());
        }
    }
}
