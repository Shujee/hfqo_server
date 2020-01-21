<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    public function Access()
    {
        return $this->belongsTo('App\Access');
    }

    public function User()
    {
        return $this->Access->User;
    }

    public function Exam()
    {
        return $this->Access->Exam;
    }
}