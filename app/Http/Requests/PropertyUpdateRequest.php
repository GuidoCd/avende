<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PropertyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $property = $this->route('property');
        return $property && $property->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'property_type_id' => 'nullable|exists:property_types,id',
            'property_status_id' => 'nullable|exists:property_statuses,id',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'surface_total' => 'nullable|numeric',
            'surface_covered' => 'nullable|numeric',
            'rooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'half_bathrooms' => 'nullable|integer|min:0',
            'garage' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|exists:currencies,code',
            'common_expenses' => 'nullable|numeric|min:0',
            'publishing_status' => 'nullable|string|in:draft,pending_review,published,rejected,paused',
            'is_active' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
            'main_image_uuid' => 'nullable|string',
            'deleted_image_uuids' => 'nullable|array',
            'deleted_image_uuids.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:10240',
        ];
    }
}
