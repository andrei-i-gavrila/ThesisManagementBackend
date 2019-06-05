<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


/**
 * Class RegisterRequest
 * @package App\Http\Requests\Auth
 *
 * @property-read string $name
 * @property-read string $email
 * @property-read string $password
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|exists:users,email,activated,0',
            'password' => 'required'
        ];
    }
}
