<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Traits\ResponseTrait;
use App\Http\Requests\LoginRequest;
use App\Repositories\AuthRepositoy;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{

    use ResponseTrait;

    public function __construct(private AuthRepositoy $auth)
    {
        $this->auth = $auth;
    }

    public function show()
    {

        try {
            return $this->responseSuccess(Auth::guard()->user(), 'Profile Found Successfully');
        } catch (Exception $e) {
            return $this->responseError([], $e->getMessage());
        }
    }

    public function logout()
    {

        try {
            Auth::guard()->user()->token()->revoke();
            Auth::guard()->user()->token()->delete();
            return $this->responseSuccess(null, 'Logged out Successfully');
        } catch (Exception $e) {
            return $this->responseError([], $e->getMessage());
        }
    }
}
