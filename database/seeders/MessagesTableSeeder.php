<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use Faker\Factory as Faker;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Message::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'message' => $faker->text(1000),
                'user_ip' => $faker->ipv4,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
