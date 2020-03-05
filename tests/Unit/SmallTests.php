<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

class SmallTests extends TestCase
{
    public function testStoragePath()
    {
        $faker = new Faker;
        
        fwrite(STDERR, app_path('snapshots'));
        $this->assertTrue(true);
    }
}