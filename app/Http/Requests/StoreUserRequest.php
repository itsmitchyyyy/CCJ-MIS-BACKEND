<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use App\Enums\AccessType;

class StoreUserRequest extends FormRequest
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
            'username' => 'required|string|unique:users,username',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|phone:PH|unique:users,contact_number',
            'email' => 'required|max:255|email|unique:users,email',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => ['required','confirmed',Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'access_type' => [Rule::enum(AccessType::class)]
        ];
    }
}
