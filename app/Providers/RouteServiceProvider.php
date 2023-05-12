<?php

namespace App\Providers;

use App\Traits\ApiResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    use ApiResponse;
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
            
            Route::prefix('api/agency/')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/agency.php'));
            
            Route::prefix('api/caregiver/')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/caregiver.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
            

            Route::prefix('admin')
                ->middleware('api')
                ->namespace($this->namespace)
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
            return Limit::perMinute(60)->response(function () {
                return $this->error('Oops! Too Many Attempts. User Blocked For 1 Minute ', null, null, 429);
            });
        });

        RateLimiter::for('limited-request', function (Request $request) {
            return Limit::perMinute(20)->response(function () {
                return $this->error('Oops! Too Many Attempts. User Blocked For 1 Minute ', null, null, 429);
            });
        });
    }
}
