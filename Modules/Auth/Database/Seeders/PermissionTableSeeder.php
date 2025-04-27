<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Modules\Auth\Entities\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'administrator-list',
            'administrator-create',
            'administrator-edit',
            'administrator-show',
            'administrator-action',
            'role-list',
            'role-create',
            'role-edit',
            'role-action',
            'operator-list',
            'operator-show',
            'operator-create',
            'operator-update',
            'operator-action',
            'category-list',
            'category-create',
            'category-update',
            'category-action',
            'bundle-list',
            'bundle-show',
            'bundle-create',
            'bundle-update',
            'bundle-action',
            'region-list',
            'region-create',
            'region-update',
            'region-action',
            'customer-list',
            'customer-show',
            'customer-update',
            'customer-action',
            'order-list',
            'order-show',
            'order-create',
            'order-update',
            'order-action',
            'payment-list',
            'payment-show',
            'payment-create',
            'payment-update',
            'payment-action',
            'refund-list',
            'refund-show',
            'refund-create',
            'refund-update',
            'refund-action',
            'service-list',
            'service-create',
            'service-update',
            'service-action',
            'media-list',
            'media-create',
            'media-update',
            'media-action',
            'setting-show',
            'setting-action',
            'gateway-list',
            'gateway-create',
            'gateway-update',
            'gateway-action',
            'country-list',
            'country-create',
            'country-update',
            'country-action',
            'city-list',
            'city-create',
            'city-update',
            'city-action',
            'language-list',
            'language-create',
            'language-update',
            'language-action',
            'banner-list',
            'banner-create',
            'banner-update',
            'banner-action',
            'menu-list',
            'menu-create',
            'menu-update',
            'menu-action',
            'page-list',
            'page-create',
            'page-update',
            'page-action',
            'platform-list',
            'platform-create',
            'platform-update',
            'platform-action',
            'device-list',
            'device-create',
            'device-update',
            'device-action',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission],
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
        Cache::forget('permissions');
    }
}
