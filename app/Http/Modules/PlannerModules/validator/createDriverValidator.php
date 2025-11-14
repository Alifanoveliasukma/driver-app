<?php

namespace App\Http\Modules\PlannerModules\Validator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class CreateDriverValidator extends FormRequest
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
            'user_value' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'user_password' => 'required|string|min:6',
            'driver_status' => 'required|string|max:25',
            'xm_fleet_id' => 'nullable|integer',
            'account_no' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ];
    }
}