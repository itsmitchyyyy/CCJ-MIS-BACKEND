<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class MessageThreadResource extends JsonResource
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
            'user_one_id' => $this->user_one_id,
            'user_two_id' => $this->user_two_id,
            'last_message_at' => $this->last_message_at,
            'last_message_id' => $this->last_message_id,
            'unread_count' => $this->unread_count,
            'subject' => $this->subject,
            'user_one' => new UserResource($this->userOne),
            'user_two' => new UserResource($this->userTwo),
            'messages' => $this->whenLoaded('messages'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
