<?php namespace Kregel\Warden;

use Illuminate\Support\ServiceProvider;

class WardenServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
    $this->app->register('Kregel\\FormModel\\FormModelServiceProvider');
		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
    $loader->alias('FormModel', 'Kregel\\FormModel\\FormModel');
	}

		/**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
      $this->app->booted(function () {
          $this->defineRoutes();
      });
       
      $this->loadViewsFrom(__DIR__.'/resources/views', 'warden');
      $this->publishes([
          __DIR__.'/resources/views' => base_path('resources/views/vendor/warden'),
          __DIR__.'/config/config.php' => config_path('warden.php'),
      ]);
      // Define our custom authentication to make sure
      // that the user is logged in!
      
      $this->app['router']->middleware('custom-auth', config('warden.auth.middleware'));
  }

  /**
   * Define the UserManagement routes.
   *
   * @return void
   */
  protected function defineRoutes()
  {
      if (! $this->app->routesAreCached()) {
          $router = app('router');

          $router->group(['namespace' => 'Kregel\\Warden\\Http\\Controllers'], function ($router) {
              require __DIR__.'/Http/routes.php';
          });
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
}
