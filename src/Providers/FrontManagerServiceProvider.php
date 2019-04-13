<?php

	namespace FrontManager\Providers;

	use Illuminate\Support\ServiceProvider;

	class FrontManagerServiceProvider extends ServiceProvider
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
		protected function registerAlias()
		{
			class_alias(\FrontManager\Services\ResourcesService::class, 'ResourcesService');
			class_alias(\FrontManager\Services\MetatagService::class, 'MetatagService');
		}
		/**
		 * Register Services
		 */
		protected function registerServices()
		{
			/**
			 * Service Response
			 */
			$this->app->singleton('service.resources', 'FrontManager\Services\ResourcesService');
			$this->app->singleton('service.metatag', 'FrontManager\Services\MetatagService');
			$this->app->singleton('factory.manager', 'FrontManager\Factory\ManagerFactory');
		}
	}