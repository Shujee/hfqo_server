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

        $Uploaders = User::where('type', User::USERTYPE_UPLOADER)->pluck('id')->toArray();

        //Create 100 exams
        $TotalExams = 100;
        $this->command->getOutput()->writeln("<info>Creating exams</info>");
        $bar = $this->command->getOutput()->createProgressBar($TotalExams);
        factory(Exam::class, $TotalExams)->make()->each(function ($exam) use (&$bar, &$faker, &$Uploaders) {
            $exam->uploader_id = $faker->randomElement($Uploaders);
            $exam->save();

            //Create QAs for this exam
            factory(QA::class, $exam->qa_count)->make()->each(function ($qa, $key) use(&$exam) {
                $qa->index = $key;
                $qa->exam_id = $exam->id;
                $qa->save();
            });

            $bar->advance();
        });

        $bar->finish();
        $this->command->getOutput()->writeln("Exams created.");
    }
}