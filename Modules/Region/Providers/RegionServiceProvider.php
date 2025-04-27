<?php

namespace Modules\Region\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Region\App\Models\City;
use Modules\Region\Entities\Country;
use Modules\Region\Entities\Language;
use Modules\Region\Entities\Region;
use Modules\Region\Observers\CityObserver;
use Modules\Region\Observers\CountryObserver;
use Modules\Region\Observers\LanguageObserver;
use Modules\Region\Observers\RegionObserver;
use Modules\Region\Repositories\CityRepository;
use Modules\Region\Repositories\CountryRepository;
use Modules\Region\Repositories\Interfaces\CityRepositoryInterface;
use Modules\Region\Repositories\Interfaces\CountryRepositoryInterface;
use Modules\Region\Repositories\Interfaces\LanguageRepositoryInterface;
use Modules\Region\Repositories\Interfaces\RegionRepositoryInterface;
use Modules\Region\Repositories\Interfaces\TimeZoneRepositoryInterface;
use Modules\Region\Repositories\LanguageRepository;
use Modules\Region\Repositories\RegionRepository;
use Modules\Region\Repositories\TimeZoneRepository;

class RegionServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Region';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'region';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        Country::observe(CountryObserver::class);
        City::observe(CityObserver::class);
        Region::observe(RegionObserver::class);
        Language::observe(LanguageObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
        $this->app->bind(LanguageRepositoryInterface::class, LanguageRepository::class);
        $this->app->bind(TimeZoneRepositoryInterface::class, TimeZoneRepository::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
