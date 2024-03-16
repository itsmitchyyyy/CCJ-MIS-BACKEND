<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class SubjectResource extends JsonResource
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
            'user' => new UserResource($this->user),
            "description" =>  $this->description,
            "code" =>  $this->code,
            "name" =>  $this->name,
            "units" =>  $this->units,
            "time_start" =>  $this->time_start,
            "time_end" =>  $this->time_end,
            "room" => $this->room,
            "days" => $this->days,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
