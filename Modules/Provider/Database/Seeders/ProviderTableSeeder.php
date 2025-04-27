<?php

namespace Modules\Provider\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Provider\Entities\Provider;

class ProviderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $providers = [
            ['name' => 'KarTat (Stock)', 'email' => 'info@kartat.io', 'gateway_ids' => [1]],
            ['name' => 'OBR.TAXI', 'email' => 'info@obr.taxi', 'gateway_ids' => [2,3]],
        ];

        foreach($providers as $provider) {
            Provider::updateOrCreate(
                ['name' => $provider['name'], 'email' => $provider['email']],
                $provider + ['password' => Hash::make(Str::random(8)), 'status' => 1]
            );
        }
    }
}
