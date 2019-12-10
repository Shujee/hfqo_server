<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Upload extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $U = $this->User();
        $E = $this->Exam();

        return [
            'user_name' => $U == null ? '' : $U->name,
            'user_email' => $U == null ? '' : $U->email,
            'exam_name' => $E == null ? '' : $E->name,
            'ip' => $this->ip,
            'machine_name' => $this->machine_name,
            'created_at' => $this->created_at,
            'age' => $this->created_at->diffForHumans(),
        ];
    }
}
