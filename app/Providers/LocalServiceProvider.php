<?php namespace App\Providers;

use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class LocalServiceProvider extends ServiceProvider
{
    // add any local only servive providers here:
    protected $providers = [
        \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        \Barryvdh\Debugbar\ServiceProvider::class,
        \Laravel\Tinker\TinkerServiceProvider::class,
//        DuskServiceProvider::class
    ];

    // and local only aliases here:
    protected $aliases = [
        'Debugbar' => \Barryvdh\Debugbar\Facade::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(config('app.debug') == true && config('app.sql_debug') == true)
        {
            DB::listen(function ($query)
            {
                Log::debug($query->sql, ['bindings' => $query->bindings, 'time' => $query->time]);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal() && !empty($this->providers))
        {
            foreach ($this->providers as $provider)
            {
                $this->app->register($provider);
            }

            if (!empty($this->aliases))
            {
                foreach ($this->aliases as $alias => $facade)
                {
                    $this->app->alias($alias, $facade);
                }
            }
        }
    }
}
