<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RequestFacilityStatus;

class StoreRequestFacilityRequest extends FormRequest
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
            'reservation_date' => 'sometimes|required|date',
            'reservation_time' => 'required_with:reservation_date|date',
            'user_id' => 'required|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
            'approved_date' => 'nullable|date',
            'borrowed_date' => 'sometimes|required|date',
            'reason' => 'nullable|string',
            'status' =>  ['sometimes', 'required', Rule::enum(RequestFacilityStatus::class)],
        ];
    }
}
