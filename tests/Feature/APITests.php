<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class APITests extends TestCase
{
/**
     * Base login
     *
     * @return void
     */
    public function testLogin()
    {
        $params = [
            'email' => 'up@hfqserver.com',
            'password' => '12345678',
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/v1/login', $params);

        $res = json_decode($response->getContent(), true);

        if(array_key_exists('exception', $res))
        {
            fwrite(STDERR, $res['message']);
            $this->assertTrue(false);
        }
        else {
            $response->assertStatus(200);
        }
    }

    /**
     * Upload a new exam.
     *
     * @return void
     */
    public function testUploadExam()
    {
        //Test Uploader user
        $user = \App\User::where('email', 'up@hfqserver.com')->first();

        Storage::fake('xps');
        Storage::fake('xml');

        $xps = UploadedFile::fake()->create('test.xps', 200); //size in kb
        $xml = UploadedFile::fake()->create('test.xml', 15); //size in kb

        $params = [
            'number' => '123-460',
            'name' => 'New Exam 123',
            'description' => 'New exam for testing',
            'qa_count' => 5,
            'origfilename' => 'Something.docx',
            'qas' => json_encode(factory(\App\QA::class, 5)->make()->each(function ($qa, $key) { 
                $qa->index = $key;
            } )),
            'xps_content' => $xps,
            'xml_content' => $xml,
        ];

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/v1/exam', $params);

        $res = json_decode($response->getContent(), true);

        if(array_key_exists('exception', $res))
            fwrite(STDERR, $res['message']);
        else {
            $response->assertStatus(201)->assertJson(['name' => 'New Exam 123']);

            $this->assertFileExists($xps);
            $this->assertFileExists($xml);
        }
    }

    public function testUpdateExamFiles()
    {
        //Test Uploader user
        $user = \App\User::where('email', 'up@hfqserver.com')->first();

        Storage::fake('xps');
        Storage::fake('xml');

        $xps = UploadedFile::fake()->create('test2.xps', 200); //size in kb
        $xml = UploadedFile::fake()->create('test2.xml', 15); //size in kb

        $params = [
            'qa_count' => 5,
            'remarks' => 'UPDATED',
            'origfilename' => 'Something.docx',
            'qas' => json_encode(factory(\App\QA::class, 5)->make()->each(function ($qa, $key) { 
                $qa->index = $key;
            } )),
            'xps_content' => $xps,
            'xml_content' => $xml,
        ];

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/v1/exam/6/update_files', $params);

        $res = $response->getContent();

        $this->assertTrue($res == 'true');

        if($res !== 'true') {
            $data = json_decode($res, true);
            fwrite(STDERR, $data['message']);
        }
        else {
           $this->assertFileExists($xps);
            $this->assertFileExists($xml);
        }
    }

    
    /**
     * Upload a new exam.
     *
     * @return void
     */
    public function testDownloadExam()
    {
        //Test Uploader user
        $user = \App\User::where('email', 'down@hfqserver.com')->first();

        $params = [
            'machine_name' => 'SHUJAAT-PC',
        ];

        //get this user's accesses
        $Acc = $user->Accesses()->first();

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->post("/api/v1/access/{$Acc->id}/download", $params);

        $data = $response->getContent()
        ;
        $res = json_decode($data, true);

        if(array_key_exists('exception', $res))
            fwrite(STDERR, $res['message']);
        else {
            $response->assertStatus(200);
        }
    }

    public function testExamNumberExists()
    {
        //Test Uploader user
        $user = \App\User::where('email', 'up@hfqserver.com')->first();
        
        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/v1/exam_number_exists/123-476');

        $res = json_decode($response->getContent(), true);
        fwrite(STDERR, json_encode($res));

        //$response->assertStatus(200)->assert(['name' => 'New Exam 123']);
    }
}
