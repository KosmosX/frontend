<?php

	namespace Kosmosx\Frontend;

	use Kosmosx\Frontend\FrontendFactoryInterface;
	use Kosmosx\Frontend\Invoker\FrontendInvoker;
	use Kosmosx\Frontend\Services\Metatags\ExtratagsFrontend;
	use Kosmosx\Frontend\Services\Metatags\MetatagsFrontend;
	use Kosmosx\Frontend\Services\Metatags\OpenGraphFrontend;
	use Kosmosx\Frontend\Services\Resources\CssFrontend;
	use Kosmosx\Frontend\Services\Resources\JsFrontend;
	use Kosmosx\Frontend\Services\Resources\JsVarsFrontend;
	use Kosmosx\Frontend\Services\Resources\ScriptsFrontend;
	use Kosmosx\Frontend\Services\Resources\StylesheetsFrontend;

	class FrontendFactory implements FrontendFactoryInterface
	{
		public function scripts(): FrontendInvoker
		{
			return new FrontendInvoker(new ScriptsFrontend());
		}

		public function js(): FrontendInvoker
		{
			return new FrontendInvoker(new JsFrontend());
		}

		public function jsVars(): FrontendInvoker
		{
			return new FrontendInvoker(new JsVarsFrontend());
		}

		public function css(): FrontendInvoker
		{
			return new FrontendInvoker(new CssFrontend());
		}

		public function style(): FrontendInvoker
		{
			return new FrontendInvoker(new StylesheetsFrontend());
		}

		public function metatags(): FrontendInvoker
		{
			return new FrontendInvoker(new MetatagsFrontend());
		}

		public function extratags(): FrontendInvoker
		{
			return new FrontendInvoker(new ExtratagsFrontend());
		}

		public function opengraph(): FrontendInvoker
		{
			return new FrontendInvoker(new OpenGraphFrontend());
		}
	}