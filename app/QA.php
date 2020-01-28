<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\QA
 *
 * @property int $id
 * @property int $exam_id
 * @property int $index
 * @property string $question
 * @property string|null $choices
 * @property string|null $answer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QA newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QA newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QA query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QA whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QA whereChoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QA whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QA whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QA whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QA whereQuestion($value)
 * @mixin \Eloquent
 */
class QA extends Model
{
    protected $table = 'qas';

    public $timestamps = false;

    protected $fillable = ['question', 'answer', 'choices', 'index', 'exam_id'];
}
