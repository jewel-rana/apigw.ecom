<?php

namespace Modules\CMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Banner\Entities\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'main',
                'label' => 'Main banner (Top)'
            ]
        ];

        foreach($banners as $banner) {
            if(!Banner::where('name', $banner['name'])->count()) {
                Banner::create($banner);
            }
        }
    }
}
