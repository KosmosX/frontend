<?php

	namespace Kosmosx\Frontend;

	use Kosmosx\Frontend\Invoker\FrontendInvoker;

	interface FrontendFactoryInterface
	{
		public function scripts(): FrontendInvoker;

		public function js(): FrontendInvoker;

		public function jsVars(): FrontendInvoker;

		public function css(): FrontendInvoker;

		public function style(): FrontendInvoker;

		public function metatags(): FrontendInvoker;

		public function extratags(): FrontendInvoker;

		public function opengraph(): FrontendInvoker;
	}