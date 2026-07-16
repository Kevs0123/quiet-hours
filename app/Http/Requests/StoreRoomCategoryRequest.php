<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100', 'unique:room_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a category name.',
            'name.min' => 'The category name must be at least 3 characters.',
            'name.max' => 'The category name may not exceed 100 characters.',
            'name.unique' => 'This category already exists.',
            'description.max' => 'The description may not exceed 1000 characters.',
        ];
    }
}
