<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QA extends Model
{
    public $timestamps = false;

    protected $fillable = ['question', 'answer', 'choices', 'index', 'upload_id'];
}
