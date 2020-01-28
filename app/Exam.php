<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Exam
 *
 * @property int $id
 * @property string $name
 * @property string $xps_file_name
 * @property string $xml_file_name
 * @property int $qa_count
 * @property int $is_expired
 * @property int $uploader_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Access[] $Accesses
 * @property-read int|null $accesses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\QA[] $QAs
 * @property-read int|null $q_as_count
 * @property-read \App\User $Uploader
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam notExpired()
 * @method static \Illuminate\Database\Query\Builder|\App\Exam onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereIsExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereQaCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereUploaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereXmlFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereXpsFileName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Exam withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Exam withoutTrashed()
 * @mixin \Eloquent
 */
class Exam extends Model
{
    use SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number', 'name', 'description', 'is_expired', 'remarks',
    ];

    public function scopeNotExpired($query)
    {
        return $query->where('is_expired', false);
    }

    public function Accesses()
    {
        return $this->hasMany('App\Access');
    }

    public function Uploader()
    {
        return $this->belongsTo('App\User');
    }

    public function QAs()
    {
        return $this->hasMany('App\QA');
    }

    /**
     * Returns first Access row for the specified user and exam
     * that is currently valid, i.e. current date/time falls within 
     * "start" and "end" values of the Access row.
     * @param [type] $user_id
     * @return void
    */
    public function GetFirstValidAccess($user_id) {
        return $this->Accesses()->
            where('user_id', $user_id)->
            where('exam_id', $this->id)->
            where('start', '<=', now())->
            where('end', '>=', now())->first();
    }

    /**
     * Returns true if an Access row exists for this Exam and the specified user.
     *
     * @param [type] $user_id
     * @return void
     */
    public function ValidAccessExists($user_id) {
        return $this->Accesses()->
            where('user_id', $user_id)->
            where('exam_id', $this->id)->
            where('start', '<=', now())->
            where('end', '>=', now())->exists();
    }
}
