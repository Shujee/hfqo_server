<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_expired',
    ];

    public function scopeNotExpired($query)
    {
        return $query->where('is_expired', false);
    }

    public function Accesses()
    {
        return $this->hasMany('App\Access');
    }
}
