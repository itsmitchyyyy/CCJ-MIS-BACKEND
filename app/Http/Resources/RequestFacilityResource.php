<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FacilityResource;
use App\Http\Resources\UserResource;

class RequestFacilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'facility_id' => $this->facility_id,
            'facility' => new FacilityResource($this->facility),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->user),
            'approved_by' => $this->approved_by,
            'approved_by_user' => new UserResource($this->approvedBy),
            'reservation_date' => $this->reservation_date,
            'approved_date' => $this->approved_date,
            'borrowed_date' => $this->borrowed_date,
            'returned_date' => $this->returned_date,
            'status' => $this->status,
            'reason' => $this->reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
