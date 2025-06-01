<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'date' => $this->date,
            'check_in' => $this->check_in?->format('F d, Y H:i'),
            'check_out' => $this->check_out?->format('F d, Y H:i'),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('F d, Y H:i A'),
            'updated_at' => $this->updated_at?->format('F d, Y H:i A'),
        ];
    }
}
