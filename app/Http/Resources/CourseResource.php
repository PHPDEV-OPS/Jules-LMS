<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'course_code' => $this->course_code,
            'course_name' => $this->course_name,
            'credits' => $this->credits,
            'enrollment_details' => $this->whenPivotLoaded('enrollments', function () {
                return [
                    'enrolled_on' => $this->pivot->enrolled_on,
                    'status' => $this->pivot->status,
                ];
            }),
        ];
    }
}