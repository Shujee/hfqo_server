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

        //CREATE MAIN TEST USER
        $u2 = new App\User;
        $u2->name = 'Test User';
        $u2->email = 'shujee@gmail.com';
        $u2->password = \Illuminate\Support\Facades\Hash::make('12345678');
        $u2->save();       
        $this->command->getOutput()->writeln("<info>Created test user</info>");

        $bar->finish();
        $this->command->getOutput()->writeln("Users created.");
    }
}
