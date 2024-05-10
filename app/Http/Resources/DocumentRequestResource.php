<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\UserResource;

class DocumentRequestResource extends JsonResource
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
            'document_id' => $this->document_id,
            'document' => new DocumentResource($this->document),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->user),
            'request_count' => $this->request_count,
            'status' => $this->status,
            'reason' => $this->reason,
            'rejected_reason' => $this->rejected_reason,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
