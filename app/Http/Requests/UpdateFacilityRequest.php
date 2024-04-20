<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\FacilityStatus;
use App\Enums\EquipmentStatus;

class UpdateFacilityRequest extends FormRequest
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
            'status' => ['sometimes', 'required', Rule::enum(FacilityStatus::class)],
            'request_id' => 'sometimes|required|exists:request_facilities,id',
            'equipmentStatus' => ['required_with:request_id', Rule::enum(EquipmentStatus::class)],
        ];
    }
}
