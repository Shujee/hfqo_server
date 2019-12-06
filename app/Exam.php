<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    public function scopeNotExpired($query)
    {
        return $query->where('is_expired', false);
    }

    public function Accesses()
    {
        return $this->hasMany('App\Access');
    }
}
