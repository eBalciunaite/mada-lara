<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        // \App\Models\User::factory(10)->create();

        DB::table('users')->insert([
            'name' => 'Egle',
            'email' => 'balciunaitegle@gmail.com',
            'password' => Hash::make('123'),
        ]);

        
        foreach(range(1, 20) as $_){
            DB::table('masters')->insert([
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
            ]);
        }

        $types = ['Shorts', 'Dress', 'Pants', 'Jumper', 'Pullover', 'Overall', 'Skirt', 'Bikini', 'Tshirt', 'Kimono', 'Strings', 'Panties'];
        foreach(range(1, 200) as $_){
            DB::table('outfits')->insert([
                'type' => $types[rand(0, count($types) - 1)],
                'color' => $faker->safeColorName,
                'photo' => rand(0, 2) ? $faker->imageUrl(200, 300) : null,
                'size' => rand(4, 24),
                'about' => $faker->realText(300, 5),
                'master_id' => rand(1, 20)

            ]);
        }
    }
}
