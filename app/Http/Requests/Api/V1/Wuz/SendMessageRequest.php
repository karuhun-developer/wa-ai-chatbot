<?php

namespace App\Http\Requests\Api\V1\Wuz;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'phone' => 'required|numeric',
            'type' => 'required|in:text,image,video,button',
            'message' => 'required_if:type,text,button|string',
            'link_preview' => 'required_if:type,text|boolean',
            'image' => 'required_if:type,image|file|image|max:2048', // 2MB max
            'video' => 'required_if:type,video|file|mimes:mp4,avi,mov,wmv|max:5120', // 5MB max
            'caption' => 'nullable|string',
            'buttons' => 'required_if:type,button|array',
            'buttons.*.id' => 'required_with:buttons|string',
            'buttons.*.text' => 'required_with:buttons|string',
        ];
    }
}
