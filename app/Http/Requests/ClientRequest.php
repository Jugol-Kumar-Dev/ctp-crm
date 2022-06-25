<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => ['required', 'max:30','string'],
            "email" => ['required', 'email'],
            "secondary_email" => ['nullable','email'],
            "phone" => ['required', 'regex:/(^([+]{1}[8]{2}|0088)?(01){1}[3-9]{1}\d{8})$/'],
            "secondary_phone" => ['nullable','regex:/(^([+]{1}[8]{2}|0088)?(01){1}[3-9]{1}\d{8})$/'],
            "company" => ['nullable','string'],
            "address" => ['nullable','string', 'max:150'],
            "note" => ['nullable','string'],
            "status" => ['nullable','string'],
            "agents" => ['nullable','array']
        ];
    }
}
