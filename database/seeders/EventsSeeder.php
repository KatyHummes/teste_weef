<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            DB::table('events')->insert([
                'name' => $faker->name,
                'date' => $faker->date,
                'responsible' => $faker->name,
                'phone' => $faker->phoneNumber,
                'state' => $faker->state,
                'city' => $faker->city,
                'neighborhood' => $faker->city,
                'street' => $faker->city,
                'number' => $faker->numberBetween(100, 2000),
                'complement' => $faker->city(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
