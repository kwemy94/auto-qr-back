<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:5000',
        ];
    }
}
