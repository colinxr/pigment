<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as Request;

class FormRequest extends Request
{

  protected function failedValidation(Validator $validator)
  {
    $response = new JsonResponse([
      'success' => false,
      'message' => 'Validation errors occurred',
      'errors' => $validator->errors(),
    ], 422);

    throw new HttpResponseException($response);
  }
}
