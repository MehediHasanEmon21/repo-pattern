<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ApiFormRequest extends FormRequest
{   
    use ResponseTrait;
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->responseError((new ValidationException($validator))->errors())
        );
            
    }
}
