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

    /**
     * Returns first Access row for the specified user and exam
     * that is currently valid, i.e. current date/time falls within 
     * "start" and "end" values of the Access row.
     * @param [type] $user_id
     * @return void
    */
    public function GetFirstValidAccess($user_id, $exam_id) {
        return $this->Accesses()->
            where('user_id', $user_id)->
            where('exam_id', $exam_id)->
            where('start', '<=', now())->
            where('end', '>=', now())->first();
    }
}
