<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Hash;

class NewUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users|email',
            'username' => 'required|unique:users',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();

        // Hash the user's password
        $validated['password'] = Hash::make($validated['password']);

        return $validated;
    }
}
