<?php

namespace Tests\Unit;

use App\Download;
use App\Exam;
use App\Http\Controllers\RequestIP;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\SlackAgent;
use App\Notifications\ExamDownloaded;
use App\Notifications\ExamUploaded;
use App\Notifications\GenericException;
use App\Notifications\ResultUploaded;
use App\Notifications\UserLogin;
use App\Upload;
use Exception;

class SlackTests extends TestCase
{
    use RequestIP;

    public function testThrow()
    {
        try {
            throw new Exception('Division by zero.');
        } catch (Exception $e) {
            (new SlackAgent())->notify(new GenericException('123.123.123.123', \App\User::find(1), $e));    
        }
        
        $this->assertTrue(true);
    }

    public function testLogin()
    {
        (new SlackAgent())->notify(new UserLogin('Some Test User', '123.123.123.123'));
        $this->assertTrue(true);
    }
    
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
