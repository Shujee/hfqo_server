<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;

    public const USERTYPE_ADMIN = 1;
    public const USERTYPE_UPLOADER = 2;
    public const USERTYPE_DOWNLOADER = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Accesses()
    {
        return $this->hasMany('App\Access');
    }

    public function isAdmin() 
    {
        return $this->type == User::USERTYPE_ADMIN;
    }

    public function isUploader()
    {
        return $this->type == User::USERTYPE_UPLOADER;
    }

    public function myExamsDL()
    {
        return $this->Accesses()->with('exam:id,name')->
                    where('start', '<=', now())->
                    where('end', '>=', now())->
                    select(['id', 'exam_id'])->
                    get()->
                    filter(function($value, $key) { return !$value->is_expired;})->
                    pluck('exam.name', 'id');
    }

    public function MyExamsUL()
    {
        return Exam::where('is_expired', false)->
                        where('uploader_id', $this->id)->
                        pluck('name', 'id');;
    }
}