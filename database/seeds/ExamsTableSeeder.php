<?php

namespace App;

use Illuminate\Database\Seeder;

class ExamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        //Create 100 exams
        $TotalExams = 100;
        $this->command->getOutput()->writeln("<info>Creating exams</info>");
        $bar = $this->command->getOutput()->createProgressBar($TotalExams);
        factory(Exam::class, $TotalExams)->create()->each(function ($e) use (&$bar) {
            $bar->advance();
        });

        $bar->finish();
        $this->command->getOutput()->writeln("Exams created.");
    }
}
