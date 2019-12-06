<?php
namespace App;

use Illuminate\Database\Seeder;

class AccessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        //get all user ids except admin
        $users = User::where('id', '!=', 1)->pluck('id')->toArray();
        $exams = Exam::where('is_expired', false)->pluck('id')->toArray();

        //Create 100 exams
        $TotalAccesses = 100;
        
        $this->command->getOutput()->writeln("<info>Creating Access Rules</info>");
        $bar = $this->command->getOutput()->createProgressBar($TotalAccesses);

        factory(Access::class, $TotalAccesses)->make()->each(function ($acc) use (&$bar, &$faker, &$users, &$exams) {
            $acc->user_id = $faker->randomElement($users);
            $acc->exam_id = $faker->randomElement($exams);
            $acc->start = $faker->dateTimeBetween($startDate = '-2 months', $endDate = '2 months', $timezone = null);
            $acc->end = $acc->start->addDays($faker->numberBetween($min = 3, $max = 10));

            $acc->save();

            $bar->advance();
        });

        $bar->finish();
        $this->command->getOutput()->writeln("Exams created.");
    }
}
