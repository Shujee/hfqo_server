<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    public function Access()
    {
        return $this->belongsTo('App\Access');
    }
}
