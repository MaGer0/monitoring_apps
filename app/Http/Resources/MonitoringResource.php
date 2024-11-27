<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitoringResource extends JsonResource
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
            'title' => $this->title,
            'Teacher' => $this->whenLoaded('teacher'),
            'description' => $this->description,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'not_presents' => $this->whenLoaded('students', function () {
                return collect($this->students)->map(function ($student) {
                    return new StudentResource($student->student);
                });
            }),
        ];
    }
}
