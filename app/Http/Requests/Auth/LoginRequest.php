<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            // 'email' => 'nullable|email|required_without_all:phone',
            // 'phone' => 'nullable|regex:/^[0-9]{8,15}$/|required_without_all:email',// RegEx à modifier pour optimiser la gestion des numéros de téléphone
            'email' => 'required_without:phone|email|exists:users,email',
            'phone' => 'required_without:email|string|exists:users,phone',
            'password' => 'required|string',
        ];
    }

    public function messages()
{
    return [
        'email.required_without' => 'Email ou numéro de téléphone requis.',
        'email.email' => 'Format de l’email invalide.',
        'email.exists' => 'Email/téléphone ou mot de passe incorrect.',
        'phone.required_without' => 'Numéro de téléphone ou email requis.',
        'phone.exists' => 'Email/téléphone ou mot de passe incorrect.',
        'password.required' => 'Mot de passe requis.',
    ];
}
}
