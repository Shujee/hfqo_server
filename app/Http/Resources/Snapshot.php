<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Snapshot extends JsonResource
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
            'timestamp' => $this->timestamp,
            'filename' => env('APP_URL') . '/' . $this->filename,
            'thumb_filename' => env('APP_URL') . '/' . $this->thumb_filename,
        ];
    }
}