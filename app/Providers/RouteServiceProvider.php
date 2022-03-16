<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->mapApiRoutes();
        $this->mapWebRoutes();

    }

    protected function mapApiRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::prefix('api')
                 ->domain($domain)
                 ->middleware('api')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/central_api.php'));
        }

        // Map the tenant routes
        Route::prefix('api')
            ->middleware([
                'api',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class
            ])
            ->namespace($this->namespace)
            ->group(base_path('routes/tenant_api.php'));
    }

    protected function mapWebRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::middleware('web')
                ->domain($domain)
                ->namespace($this->namespace)
                ->group(base_path('routes/central_web.php'));
        }

        // Map the tenant routes
        Route::middleware([
                'web',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class
            ])
            ->namespace($this->namespace)
            ->group(base_path('routes/tenant_web.php'));
    }

    protected function centralDomains() : array
    {
        return config('tenancy.central_domains');
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}