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

        //CREATE TEST UPLOADER
        $u2 = new User;
        $u2->name = 'Test Uploader';
        $u2->type = \App\User::USERTYPE_UPLOADER;
        $u2->email = 'up@hfqserver.com';
        $u2->password = \Illuminate\Support\Facades\Hash::make('12345678');
        $u2->save();       
        $this->command->getOutput()->writeln("<info>Created test uploader</info>");
        
        //CREATE TEST DOWNLOADER
        $u2 = new User;
        $u2->name = 'Test Downloader';
        $u2->type = \App\User::USERTYPE_DOWNLOADER;
        $u2->email = 'down@hfqserver.com';
        $u2->password = \Illuminate\Support\Facades\Hash::make('12345678');
        $u2->save();       
        $this->command->getOutput()->writeln("<info>Created test downloader</info>");

        $bar->finish();
        $this->command->getOutput()->writeln("Users created.");
    }
}
