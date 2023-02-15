<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenResult;

class AuthRepositoy {

    public function login(array $data): array
    {

        $user = $this->findUserByEmail($data['email']);

        if(!$user){
            throw new Exception('User email not exists', 404);
        }

        if(!$this->isValidPassword($data, $user)){
            throw new Exception('Passwod not match', 401);
        }

        $tokenInstance = $this->createAuthToken($user);
        return $this->getAuthData($user, $tokenInstance);
        

    }

    public function register(array $data): array
    {

        $user = User::create($this->prepareDataForRegister($data));

        if(!$user){
            throw new Exception('Sorry, User does not reister, please try again', 404);
        }

        $tokenInstance = $this->createAuthToken($user);
        return $this->getAuthData($user, $tokenInstance);
        

    }

    private function findUserByEmail(string $email): ?User
    {
        return User::where('email',$email)->first();
    }

    private function isValidPassword(array $data, User $user): bool
    {
        return Hash::check($data['password'], $user->password);
    }

    private function createAuthToken(User $user): PersonalAccessTokenResult
    {
        return $user->createToken('authToken');
    }

    private function getAuthData(User $user,  PersonalAccessTokenResult $tokenInstance)
    {

        return [
            'user' => $user,
            'access_token' => $tokenInstance->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenInstance->token->expires_at)->toDateTimeString()
        ];

    }

    private function prepareDataForRegister(array $data): array
    {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];
    } 

}