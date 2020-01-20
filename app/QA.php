<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QA extends Model
{
    protected $table = 'qas';

    public $timestamps = false;

    protected $fillable = ['question', 'answer', 'choices', 'index', 'upload_id'];
}
