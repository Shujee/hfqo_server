<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    public function Access()
    {
        return $this->belongsTo('App\Access');
    }
}