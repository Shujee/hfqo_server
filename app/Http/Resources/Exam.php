<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Exam extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'xps_file_name' => $this->xps_file_name,
            'xml_file_name' => $this->xml_file_name,
            'qa_count' => $this->qa_count,
            'is_expired' => $this->is_expired,
            'updated_at' => $this->updated_at,
            'age' => $this->updated_at->diffForHumans(),
            'uploader' => $this->Uploader->name
        ];
    }
}
