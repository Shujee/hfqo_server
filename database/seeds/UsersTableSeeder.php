<?php

namespace App;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        //Create 20 users
        $TotalUsers = 20;
        $this->command->getOutput()->writeln("<info>Creating other users</info>");
        $bar = $this->command->getOutput()->createProgressBar($TotalUsers);
        factory(User::class, $TotalUsers)->create()->each(function ($u) use (&$bar) {
            $bar->advance();
        });

        $bar->finish();
        $this->command->getOutput()->writeln("Users created.");
    }
}
