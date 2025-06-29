<?php

namespace App\Http\Resources\Operator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeOperatorResource extends JsonResource
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
            'course' => [
                'id' => $this->course?->id,
                'name' => $this->course?->name,
                'code' => $this->course?->code,
                'credit' => $this->course?->credit,
            ],
            'letter' => $this->letter,
            'weight_of_value' => $this->weight_of_value,
            'grade' => $this->grade,
            'created_at' => $this->created_at,
        ];
    }
}
