<?php

namespace Tests\Unit;

use App\Download;
use App\Exam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\SlackAgent;
use App\Notifications\ExamDownloaded;
use App\Notifications\ExamUploaded;
use App\Notifications\ResultUploaded;
use App\Upload;

class SlackTests extends TestCase
{
    public function testUploadExam()
    {
        $UL = Exam::find(1);

        (new SlackAgent())->notify(new ExamUploaded($UL, true));

        $this->assertTrue(true);
    }

    public function testDownloadExam()
    {
        $DL = Download::find(1);

        (new SlackAgent())->notify(new ExamDownloaded($DL));

        $this->assertTrue(true);
    }

    public function testUploadResult()
    {
        $UL = Upload::find(1);

        (new SlackAgent())->notify(new ResultUploaded($UL));

        $this->assertTrue(true);
    }
}
