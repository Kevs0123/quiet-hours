<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingConfirmationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * No file rules here — file validation is done manually in the controller
     * so we avoid the 'uploaded' rule which fires on PHP-level rejections and
     * shows a confusing error even when the file is fine.
     */
    public function rules(): array
    {
        return [];
    }
}
