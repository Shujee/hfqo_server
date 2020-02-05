<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Used by HFQApp to show the list of exams that user can download
 */
class AccessibleExam extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'access_id' => $this->id,
            'end' => $this->end->toDateString(),
            'exam_id' => $this->exam->id,
            'number' => $this->exam->number,
            'name' => $this->exam->name,
            'qa_count' => $this->exam->qa_count,
            'updated_at' => $this->exam->updated_at->toDateString(),
            'remarks' => $this->exam->remarks,
        ];
    }
}
