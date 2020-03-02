<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Upload
 *
 * @property int $id
 * @property int $access_id
 * @property string $ip
 * @property string $machine_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $city
 * @property string $country
 * @property-read \App\Access $Access
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload whereAccessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload whereMachineName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Upload whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Upload extends Model
{
    public function Access()
    {
        return $this->belongsTo('App\Access')->withTrashed();
    }

    public function User()
    {
        return $this->Access->User();
    }

    public function Exam()
    {
        return $this->Access->Exam();
    }
}