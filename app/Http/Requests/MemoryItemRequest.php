<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemoryItemRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'file' => ['required', 'file','mimes:jpeg,png,jpg,mp4,mkv,webp', 'max:10240'], 
            'public' => ['required', 'boolean'],
            'type' => ['required', 'string', 'in:MemoryThread,TimeCapsule,Keepsake'],
            'location' => ['nullable','required_if:type,MemoryThread', 'string'],
            'date' => ['nullable','required_if:type,MemoryThread', 'date'],
            'open_date' => ['nullable','required_if:type,TimeCapsule', 'date'],
            'given_to_user_id' => ['nullable','required_if:type,Keepsake', 'exists:users,id'],
            'can_be_viewed_by' => ['nullable','required_if:public,false'],
            'can_be_viewed_by.*' => ['nullable','required_if:public,false', 'array'],
            'can_be_viewed_by.*.value' => ['nullable', 'exists:users,id'],
            'can_be_viewed_by.*.title' => ['nullable', 'string'],
        ];
    }
}
