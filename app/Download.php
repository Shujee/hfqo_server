<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Download
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download whereAccessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download whereMachineName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Download whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Download extends Model
{
    public function Access()
    {
        return $this->belongsTo('App\Access')->withTrashed();
    }

    public function User()
    {
        return $this->Access->User();
    }
}
