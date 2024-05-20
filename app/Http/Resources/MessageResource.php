<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class MessageResource extends JsonResource
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
            'to_id' => $this->to_id,
            'send_from_id' => $this->send_from_id,
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => $this->status,
            'type' => $this->type,
            'attachment' => $this->attachment,
            'read_at' => $this->read_at,
            'sent_at' => $this->sent_at,
            'to' => new UserResource($this->to),
            'send_from' => new UserResource($this->sendFrom),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
