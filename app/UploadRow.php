<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uploadrow extends Model
{
    public $timestamps = false;

    protected $fillable = ['q', 'a1', 'a2', 'a3', 'upload_id'];

    public function Upload()
    {
        return $this->belongsTo('App\Upload');
    }
}