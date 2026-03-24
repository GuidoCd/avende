<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PublisherProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Any authenticated user can hit this, the controller enforces identity
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'public_email' => ['nullable', 'email', 'max:255'],
            'public_phone' => ['nullable', 'string', 'max:50'],
            'has_whatsapp' => ['boolean'],
            'bio' => ['nullable', 'string'],
        ];
    }
}
