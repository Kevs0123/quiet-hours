<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_category_id' => ['required', 'exists:room_categories,id'],
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'room_category_id.required' => 'Please select a room category.',
            'room_category_id.exists' => 'The selected room category is invalid.',
            'name.required' => 'Please enter a room name.',
            'name.min' => 'The room name must be at least 3 characters.',
            'price_per_night.required' => 'Please enter the price per night.',
            'price_per_night.numeric' => 'The price must be a number.',
            'price_per_night.min' => 'The price cannot be negative.',
            'capacity.required' => 'Please enter the room capacity.',
            'capacity.integer' => 'Capacity must be a whole number.',
            'capacity.min' => 'Capacity must be at least 1.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Only JPG, PNG, and WEBP images are allowed.',
            'image.max' => 'The image must not be larger than 2 MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_available' => $this->boolean('is_available'),
        ]);
    }
}
