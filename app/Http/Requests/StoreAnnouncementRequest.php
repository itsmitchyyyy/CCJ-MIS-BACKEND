<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'required|string',
            'posted_by_id' => 'required|exists:users,id',
            'images' => 'nullable|array|min:1',
            'images.*' => 'file|mimes:jpeg,png,jpg,gif|max:25128|required_with:images',
            'status' => 'sometimes|required|in:active,inactive',
        ];
    }
}
