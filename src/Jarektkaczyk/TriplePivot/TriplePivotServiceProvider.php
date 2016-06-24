<?php

namespace Jarektkaczyk\TriplePivot;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Morrislaptop\LaravelFivePackageBridges\ConfigServiceProvider;
use Morrislaptop\LaravelFivePackageBridges\LaravelFivePackageBridgeTrait;

/**
 * Class TriplePivotServiceProvider
 * @package Jarektkaczyk\TriplePivot
 */
class TriplePivotServiceProvider extends ServiceProvider {
	
	use LaravelFivePackageBridgeTrait;

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 */
	public function boot() {
		$this->package( 'jarektkaczyk/triple-pivot' );
	}

	/**
	 * Register the service provider.
	 */
	public function register() {
		$this->app->booting( function () {
			$loader = AliasLoader::getInstance();
			$loader->alias( 'TriplePivot', TriplePivot::class );
		} );

		$this->app->register(ConfigServiceProvider::class);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return [ 'triple-pivot' ];
	}

}
