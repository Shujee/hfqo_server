<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $dates = [
        'start', 'end'
    ];

    public function Exam()
    {
        return $this->belongsTo('App\Exam');
    }

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function isValid($user_id)
    {
        return $this->start <= now() &&
                $this->end >= now() &&
                $this->user_id <= $user_id;
    }
}
