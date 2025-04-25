<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'email' => 'nullable|email|unique:users,email|required_without_all:phone',
            'phone' => 'nullable|regex:/^[0-9]{8,15}$/|unique:users,phone|required_without_all:email',// RegEx à modifier pour optimiser la gestion des numéros de téléphone
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L’email est requis.',
            'email.email' => 'Le format de l’email est invalide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ];
    }

    /* public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->email) && empty($this->phone)) {
                $validator->errors()->add('email', 'Email ou téléphone requis.');
                $validator->errors()->add('phone', 'Email ou téléphone requis.');
            }
        });
    } */
}
