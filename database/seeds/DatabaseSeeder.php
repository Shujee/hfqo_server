<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //CREATE ADMIN
        $u1 = new App\User;
        $u1->name = 'HFQ Admin';
        $u1->type = \App\User::USERTYPE_ADMIN;
        $u1->email = 'shujaat@hotmail.com';
        $u1->password = \Illuminate\Support\Facades\Hash::make('12345678');
        $u1->save();
        $this->command->getOutput()->writeln("<info>Created admin</info>");
        
        $this->call(App\UsersTableSeeder::class);
        $this->call(App\ExamsTableSeeder::class);
        $this->call(App\AccessesTableSeeder::class);
        $this->call(App\DownloadsTableSeeder::class);
    }
}
