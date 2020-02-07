<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewlyCreatedExam extends JsonResource
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
            'number' => $this->number,
            'name' => $this->name,
            'xps' => $this->xps_file_name,
            'xml' => $this->xml_file_name,
            'qa_count' => $this->qa_count,
            'updated_at' => $this->updated_at,
            'remarks' => $this->remarks,
            'origfilename' => $this->origfilename
        ];
    }
}
