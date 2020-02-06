<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Access[] $Accesses
 * @property-read int|null $accesses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 * @mixin \Eloquent
 */
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

    /**
     * Exams downloaded by this user
     *
     * @return void
     */
    public function DownloadedExams()
    {
        return \App\Download::
                join('accesses', 'downloads.access_id', '=', 'accesses.id')->
                join('exams', 'accesses.exam_id', '=', 'exams.id')->
                where('accesses.user_id', $this->id)->
                selectRaw('exams.id,exams.number,exams.name,exams.qa_count,downloads.ip,downloads.city,downloads.country,downloads.updated_at,\'D\' as type')->
                get();
    }
    
    /**
     * Results uploaded by this user
     *
     * @return void
     */
    public function UploadedResults()
    {
        return \App\Upload::
                join('accesses', 'uploads.access_id', '=', 'accesses.id')->
                join('exams', 'accesses.exam_id', '=', 'exams.id')->
                where('accesses.user_id', $this->id)->
                selectRaw('exams.id,exams.number,exams.name,exams.qa_count,uploads.ip,uploads.city,uploads.country,uploads.updated_at,\'R\' as type')->
                get();
    }

    /**
     * Exams uploaded by this associate
     *
     * @return void
     */
    public function UploadedExams()
    {
        return $this->hasMany('App\Exam', 'uploader_id', 'id')
                ->selectRaw('id,number,name,qa_count,is_expired,updated_at,\'U\' as type')
                ->get();
    }

    public function isAdmin() 
    {
        return $this->type == User::USERTYPE_ADMIN;
    }

    public function isUploader()
    {
        return $this->type == User::USERTYPE_UPLOADER;
    }

    /**
     * Exams that current user can download
     *
     * @return void
     */
    public function myExamsDL()
    {
        return $this->Accesses()->current()->
                whereHas('exam', function($query) { $query->whereIsExpired(false); })->
                with('exam:id,number,name,remarks,qa_count,updated_at')->
                select(['id','exam_id', 'end'])->get();
    }                 

    /**
     * Active exams owned by current user (associate)
     *
     * @return void
     */
    public function myExamsUL()
    {
        return Exam::whereIsExpired(false)->
                    whereUploaderId($this->id)->
                    select(['id', 'number', 'name', 'qa_count', 'origfilename', 'remarks', 'updated_at'])->get();
    }
}