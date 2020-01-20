<?php
namespace App;

use Illuminate\Database\Seeder;

class DownloadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $SampleCities = [
            'China' => ['Shanghai', 'Beijing', 'Tianjin', 'Shenzhen', 'Guangzhou', 'Chengdu', 'Dongguan', 'Chongqing'],
            'USA' => ['New York City', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia'],
            'Pakistan' => ['Karachi', 'Lahore', 'Faisalabad', 'Peshawar']
        ];
    
        $faker = \Faker\Factory::create();

        //get all user ids except admin
        $accesses = Access::all()->pluck('id')->toArray();

        //Create 100 exams
        $TotalDownloads = 50;
        
        $this->command->getOutput()->writeln("<info>Creating Downloads</info>");
        $bar = $this->command->getOutput()->createProgressBar($TotalDownloads);

        factory(Download::class, $TotalDownloads)->make()->each(function ($dl) use (&$bar, &$faker, &$accesses, &$SampleCities) {
            $Countries = array_keys($SampleCities);
            $RandomCountry = $Countries[array_rand($Countries)];
            $CountryCities = $SampleCities[$RandomCountry];
            $RandomCity =  $CountryCities[array_rand($CountryCities)];

            $dl->access_id = $faker->randomElement($accesses);
            $dl->ip = $faker->ipv4;
            $dl->country = $RandomCountry;
            $dl->city = $RandomCity;
            $dl->machine_name = strtoupper($faker->domainWord);

            //Download can only be created between "start" and "end" of the parent Access
            $dl->created_at = $faker->dateTimeBetween($startDate = $dl->Access->start, $endDate = $dl->Access->end, $timezone = null);
            $dl->updated_at = $dl->created_at;
            $dl->save();

            //Create Upload entry for 90% of download entries
            if($faker->boolean(90))
            {
                $Countries = array_keys($SampleCities);
                $RandomCountry = $Countries[array_rand($Countries)];
                $CountryCities = $SampleCities[$RandomCountry];
                $RandomCity =  $CountryCities[array_rand($CountryCities)];

                $ul = new Upload();

                $ul->access_id = $dl->access_id;
                $ul->ip = $dl->ip;
                $ul->country = $RandomCountry;
                $ul->city = $RandomCity;
                $ul->machine_name = strtoupper($dl->machine_name);

                //Upload can only be created AFTER download and BEFORE "end" of the parent Access
                $ul->created_at = $faker->dateTimeBetween($startDate = $dl->created_at, $endDate = $dl->Access->end, $timezone = null);
                $ul->updated_at = $ul->created_at;
    
                $ul->save();    

                //Create result rows 
                $QACount =  $dl->Access->Exam->qa_count;
                for ($i=0; $i < $QACount; $i++) { 
                    $ur = new Uploadrow();

                    $ur->upload_id = $ul->id;
                    $ur->q = $i;
                    $ur->a1 = $faker->numberBetween($min = 1, $max = $QACount);

                    if($faker->boolean(5))
                    {
                        $temp = $faker->numberBetween($min = 1, $max = $QACount);

                        while($temp == $ur->a1)
                            $temp = $faker->numberBetween($min = 1, $max = $QACount);

                        $ur->a2 = $temp;
                    }

                    if($ur->a2 != null && $faker->boolean(1))
                    {
                        $temp = $faker->numberBetween($min = 1, $max = $QACount);

                        while($temp == $ur->a1 || $temp == $ur->a2)
                            $temp = $faker->numberBetween($min = 1, $max = $QACount);

                        $ur->a3 = $temp;
                    }


                    $ur->save();
                }
            }

            $bar->advance();
        });

        $bar->finish();
        $this->command->getOutput()->writeln("Downloads created.");
    }
}