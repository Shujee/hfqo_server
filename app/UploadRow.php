<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Uploadrow
 *
 * @property int $id
 * @property int $upload_id
 * @property int $q
 * @property int $a1
 * @property int|null $a2
 * @property int|null $a3
 * @property-read \App\Upload $Upload
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Uploadrow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Uploadrow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Uploadrow query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Uploadrow whereA1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Uploadrow whereA2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Uploadrow whereA3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Uploadrow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Uploadrow whereQ($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Uploadrow whereUploadId($value)
 * @mixin \Eloquent
 */
class Uploadrow extends Model
{
    public $timestamps = false;

    protected $fillable = ['q', 'a1', 'a2', 'a3', 'upload_id'];

    public function Upload()
    {
        return $this->belongsTo('App\Upload');
    }
}