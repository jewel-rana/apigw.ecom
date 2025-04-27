<?php

namespace Modules\Order\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Order\App\Console\OrderNonePaymentStatusUpdateCommand;
use Modules\Order\App\Console\OrderProcessCommand;
use Modules\Order\App\Console\OrderPurchaseVerifyCommand;
use Modules\Order\App\Console\OrderRefundCheckCommand;
use Modules\Order\App\Console\OrderSummaryCommand;
use Modules\Order\App\Console\OrderSummaryOneTimeCommand;
use Modules\Order\App\Console\OrderPaymentVerifyCommand;
use Modules\Order\App\Console\RefundProcessCommand;
use Modules\Order\App\Console\RefundStatusVerifyCommand;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\Refund;
use Modules\Order\App\Observers\OrderObserver;
use Modules\Order\App\Policies\OrderPolicy;
use Modules\Order\App\Policies\RefundPolicy;
use Modules\Order\App\Repositories\Interfaces\RefundRepositoryInterface;
use Modules\Order\App\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\App\Repositories\RefundRepository;
use Modules\Order\App\Repositories\OrderRepository;

class OrderServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Order';

    protected string $moduleNameLower = 'order';

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected array $policies = [
        Order::class => OrderPolicy::class,
        Refund::class => RefundPolicy::class,
    ];

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/migrations'));
        Order::observe(OrderObserver::class);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(RefundRepositoryInterface::class, RefundRepository::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
         $this->commands([
             OrderPaymentVerifyCommand::class,
             OrderPurchaseVerifyCommand::class,
             OrderProcessCommand::class,
             OrderRefundCheckCommand::class,
             RefundProcessCommand::class,
             OrderNonePaymentStatusUpdateCommand::class,
             RefundStatusVerifyCommand::class,
             OrderSummaryCommand::class,
             OrderSummaryOneTimeCommand::class,
         ]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
         $this->app->booted(function () {
             $schedule = $this->app->make(Schedule::class);

             $schedule->command('order:process')
                 ->everyThirtySeconds()
                 ->withoutOverlapping()
                 ->onOneServer();

             $schedule->command('order:verify')->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->onOneServer();

             $schedule->command('order:payment-verify')->everyTwoMinutes()
                 ->withoutOverlapping()
                 ->onOneServer();

             $schedule->command('order:refund-check')->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->onOneServer();

             $schedule->command('app:order-none-payment-status-update')->hourly()
                 ->withoutOverlapping()
                 ->onOneServer();

             $schedule->command('app:refund-status-verify')
                 ->everyFiveMinutes()
                 ->onOneServer()
                 ->withoutOverlapping();

             $schedule->command('app:old-refund-verify-command')
                 ->onOneServer()
                 ->withoutOverlapping()
                 ->everyTwoMinutes();

             $schedule->command('order:summary')
                 ->onOneServer()
                 ->withoutOverlapping()
                 ->dailyAt('02:05');
         });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower.'.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('modules.namespace').'\\'.$this->moduleName.'\\'.config('modules.paths.generator.component-class.path'));
        Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
