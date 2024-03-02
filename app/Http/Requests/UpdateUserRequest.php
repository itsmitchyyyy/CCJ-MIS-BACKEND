<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|phone:PH|unique:users,contact_number,'.$this->user()->id,
            'email' => 'required|max:255|email|unique:users,email,'.$this->user()->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
