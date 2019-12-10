<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    public function Access()
    {
        return $this->belongsTo('App\Access');
    }

    public function Exam()
    {
        $Acc = Access::find($this->access_id);
        return Exam::withTrashed()->find($Acc->exam_id);
    }

    public function User()
    {
        $Acc = Access::find($this->access_id);
        return User::withTrashed()->find($Acc->user_id);
    }
}
