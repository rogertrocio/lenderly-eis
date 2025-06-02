<?php

namespace App\Http\Resources;

use App\Enums\Action;
use App\Enums\Module;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'job' => $this->job,
            'avatar' => $this->avatar,
            'avatar_url' => $this->avatar_url,
            'latest_attendance' => new AttendanceResource($this->whenLoaded('latestAttendance')),
            'is_already_checked_in' => $this->is_already_checked_in,
            'is_already_checked_out' => $this->is_already_checked_out,
            'roles' =>  RoleResource::collection($this->whenLoaded('roles')),
            'created_at' => $this->created_at?->format('F d, Y H:i A'),
            'updated_at' => $this->updated_at?->format('F d, Y H:i A'),
        ];
    }
}
