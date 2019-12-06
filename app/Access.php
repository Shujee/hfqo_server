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
}
