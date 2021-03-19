<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Access extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $U = $this->User;

        return [
            'id' => $this->id,
            'user_name' => $U == null ? '' : $U->name,
            'start' => $this->start->toIso8601String(),
            'end' => $this->end->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'age' => $this->updated_at->diffForHumans(),
        ];
    }
}