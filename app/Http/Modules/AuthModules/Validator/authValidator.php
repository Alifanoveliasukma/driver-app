<?php

namespace App\Http\Modules\AuthModules\Validator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class AuthValidator extends FormRequest
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
     * 
     */
    public function rules(): array
    {
        return [
            'role' => 'string|required',
            'org' => 'string|required',
        ];
    }
}