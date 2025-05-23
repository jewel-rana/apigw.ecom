<?php

namespace Modules\CMS\Database\Seeders;

use Illuminate\Database\Seeder;

class CMSDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call([
             BannerSeeder::class
         ]);
    }
}
