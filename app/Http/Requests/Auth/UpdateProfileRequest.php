<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateProfileRequest extends FormRequest
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
        $user = JWTAuth::user();
        
        return [
            'name'  => 'sometimes|string|max:30',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|regex:/^[0-9]{8,15}$/|unique:users,phone,' . $user->id,
        ];
    }
}
