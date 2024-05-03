<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RequestFacilityStatus;

class UpdateRequestFacility extends FormRequest
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
            'returned_date' => 'sometimes|required|date',
            'status' => ['required', Rule::enum(RequestFacilityStatus::class)],
            'rejected_reason' => 'required_if:status,rejected|string'
        ];
    }
}
