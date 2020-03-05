<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snapshot extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'filename', 'timestamp', 'download_id'
    ];
}
