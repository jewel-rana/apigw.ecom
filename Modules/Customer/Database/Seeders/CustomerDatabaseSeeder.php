<?php

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Modules\Customer\App\Models\Customer;

class CustomerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        // $faker = Faker::create();
        // for ($i = 0; $i < 1000000; $i++) {
        //     Customer::create([
        //         'name' => $faker->name,
        //         'email' => $faker->unique()->safeEmail,
        //         'mobile' => substr($faker->phoneNumber, 0, 15),
        //         'address' => $faker->address,
        //         'gender' => $faker->randomElement(['male','female']),
        //         'status' => $faker->randomElement(['pending','active','inactive']),
        //         'password' => $faker->password,
        //     ]);
        // }
    }
}
