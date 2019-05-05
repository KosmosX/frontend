<?php

	namespace Kosmosx\Frontend\Providers;

	use Illuminate\Support\ServiceProvider;
	use Kosmosx\Frontend\ProcessorInvoker;

	class FrontendServiceProvider extends ServiceProvider
	{
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->registerAlias();
			$this->registerServices();
		}
		/**
		 * Load alias
		 */
		protected function registerAlias(){}
		/**
		 * Register Services
		 */
		protected function registerServices(){}
	}