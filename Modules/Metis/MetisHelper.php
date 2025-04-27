<?php

namespace Modules\Metis;

class MetisHelper
{
    public static function loadMenus(): string
    {
        $modules = (array)json_decode(file_get_contents(__DIR__ . '/../../modules_statuses.json'));
        $views = '';
        foreach ($modules as $moduleName => $v) {
            $module = strtolower($moduleName);
            if ($module != 'metis') {
                if (view()->exists("{$module}::layouts.nav"))
                    $views .= view("{$module}::layouts.nav");
            }
        }
        return $views;
    }
}
