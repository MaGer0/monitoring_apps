<?php

namespace App\Http\Resources;

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
            'title' => $this->title,
            'Teacher' => $this->whenLoaded('teacher'),
            'description' => $this->description,
            'date' => $this->date,
            'not_presents' => $this->whenLoaded('students', function () {
                return collect($this->students)->each(function ($student) {
                    $student->student;
                    return $student;
                });
            })
        ];
    }
}
