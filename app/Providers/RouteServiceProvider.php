<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Api
            Route::domain('api.' . env('APP_DOMAIN'))
                ->prefix('auth')
                ->middleware('api')
                ->namespace($this->namespace . '\Api\Auth')
                ->group(base_path('routes/api/auth.php'));

            Route::domain('api.' . env('APP_DOMAIN'))
                ->prefix('account')
                ->middleware(['api', 'auth:api'])
                ->namespace($this->namespace . '\Api\Account')
                ->group(base_path('routes/api/account.php'));

            Route::domain('api.' . env('APP_DOMAIN'))
                ->prefix('minitutor')
                ->middleware(['api', 'auth:api', 'minitutor:active'])
                ->namespace($this->namespace . '\Api\Minitutor')
                ->group(base_path('routes/api/minitutor.php'));

            Route::domain('api.' . env('APP_DOMAIN'))
                ->middleware('api')
                ->namespace($this->namespace . '\Api\App')
                ->group(base_path('routes/api/app.php'));


            // Admin
            Route::domain('admin.' . env('APP_DOMAIN'))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::domain('admin.' . env('APP_DOMAIN'))
                ->middleware(['web', 'auth', 'can:admin access'])
                ->namespace($this->namespace . '\Admin')
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
