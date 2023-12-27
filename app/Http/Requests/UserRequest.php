<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
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
            'username' => ['required','string','max:255'],
            'email' => ['required','string', 'email','max:255', 'unique:users'],
            'password' => ['required','string','min:8', 'confirmed'],
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'company_id' => ['required','string','max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     * */
    public function messages(): array
    {
        return [
            'username.required' => 'UserName is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'company_id.required' => 'Company id is required',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     * */
    public function attributes(): array
    {
        return [
            'UserName' => 'UserName',
            'email' => 'Email',
            'password' => 'Password',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'company_id' => 'Company id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     *
     * */
    protected function failedValidation($validator){
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors], 422));
    }
}
