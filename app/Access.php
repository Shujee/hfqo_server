<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * \App\Access
 *
 * @property int $id
 * @property int $user_id
 * @property int $exam_id
 * @property \Illuminate\Support\Carbon $start
 * @property \Illuminate\Support\Carbon $end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Exam $Exam
 * @property-read \App\User $User
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Access whereUserId($value)
 * @mixin \Eloquent
 */
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

    public function Downloads()
    {
        return $this->hasMany('App\Download');
    }

    public function Uploads()
    {
        return $this->hasMany('App\Upload');
    }

    public function isValid($user_id)
    {
        return $this->start <= now() &&
                $this->end >= now() &&
                $this->user_id <= $user_id;
    }

    public function scopeCurrent($query)
    {
        return $query->where('start', '<=', now())->where('end', '>=', now());
    }
}
