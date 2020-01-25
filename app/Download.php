<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    public function Access()
    {
        return $this->belongsTo('App\Access');
    }

    public function Exam()
    {
        return $this->Access->Exam();
    }

    public function User()
    {
        return $this->Access->User();
    }
}
