<?php

namespace App\Http\Requests\Cms\Device\Device;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeviceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'ai_enabled' => 'sometimes|boolean',
            'auto_read' => 'sometimes|boolean',
        ];
    }
}
