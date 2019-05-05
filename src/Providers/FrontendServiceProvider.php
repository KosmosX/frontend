<?php

	namespace Kosmosx\Frontend\Providers;

	use Illuminate\Support\ServiceProvider;
	use Kosmosx\Frontend\FrontendFactory;

	class FrontendServiceProvider extends ServiceProvider
	{
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			class_alias(FrontendFactory::class, 'FrontendFactory');

			$this->app->bind('factory.frontend', function ($app) {
				return new FrontendFactory();
			});		
		}
	}