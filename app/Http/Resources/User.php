<?php

namespace App\Http\Resources;

use App\User as AppUser;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $activity = null;
        if($this->type == \App\User::USERTYPE_UPLOADER)
        {
            //Fetch exams uploaded by this associate
            $activity = $this->UploadedExams();
        }
        else if($this->type == \App\User::USERTYPE_DOWNLOADER)
        {
            //Fetch exams downloaded by this user
            $activity1 = $this->DownloadedExams();

            //Fetch results uploaded by this user
            $activity2 = $this->UploadedResults();

            $activity = $activity1->merge($activity2);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'updated_at' => $this->updated_at,
            'age' => $this->updated_at->diffForHumans(),
            'type' => $this->type,
            'activity' => $activity,
        ];
    }
}