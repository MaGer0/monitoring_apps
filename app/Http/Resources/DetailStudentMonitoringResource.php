<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailStudentMonitoringResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'monitoring_id' => $this->monitoring_id,
            'student' => $this->whenLoaded('student'),
            'keterangan' => $this->keterangan
        ];
    }
}
