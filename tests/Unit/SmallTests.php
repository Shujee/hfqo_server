<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;

class SmallTests extends TestCase
{
    public function testStoragePath()
    {
        $faker = new Faker;
        
        fwrite(STDERR, $faker->word(1, 1));
        $this->assertTrue(true);
    }
}