<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class StudentAssignmentResource extends JsonResource
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
            'user_id' => $this->user_id,
            'student' => new UserResource($this->whenLoaded('student')),
            'assignment_id' => $this->assignment_id,
            'assignment' => new AssignmentResource($this->whenLoaded('assignment')),
            'score' => $this->score,
            'file_paths' => $this->file_paths,
            'comments' => $this->comments,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
