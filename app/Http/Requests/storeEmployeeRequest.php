<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeEmployeeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'first_name' =>'required | string | max:14',
            'last_name' =>'required | string | max:60',
            'email'=>'required|string|email |unique:employees,email',
            'department_id'=>'numeric',
            'position' =>'string',
             'project_id' =>'nullable | array',
        ];
    }
}
