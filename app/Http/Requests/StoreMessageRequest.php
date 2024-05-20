<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'to_id' => 'required|exists:users,id',
            'send_from_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'subject' => 'required|string|max:255',
            'attachment' => 'nullable|array|min:1',
            'attachment.*' => 'file|max:25128|required_with:attachment',
            'type' => 'required|in:inbox,sent',
        ];
    }
}
