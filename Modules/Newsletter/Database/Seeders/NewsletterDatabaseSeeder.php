<?php

namespace Modules\Newsletter\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Entities\Permission;

class NewsletterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(config('newsletter.permissions'))
            ->each(function ($permission, $key) {
                Permission::updateOrCreate(
                    [
                        'name' => $permission
                    ],
                    [
                        'name' => $permission
                    ]
                );
            });
    }
}
