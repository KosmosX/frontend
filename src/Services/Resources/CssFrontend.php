<?php

	namespace Kosmosx\Frontend\Services\Resources;

	use Kosmosx\Frontend\Services\FrontendService;
	use Kosmosx\Frontend\Services\FrontendServiceInterface;

	class CssFrontend extends FrontendService implements FrontendServiceInterface
	{
		protected $css = array();

		public function dump(?string $get = null): ?string
		{
			return $this->rendering($this->css, $get);
		}

		public function add(string $url, array $property = array(), string $put = 'body'): FrontendServiceInterface
		{
			$property = $this->property($property, true);

			$this->push($this->css, 'style', $put, $property, $content);
			return $this;
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function has(string $context, ?string $name = null): bool
		{
			return $this->exist($this->css,$context, $name = null);
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function forget(string $context, ?string $name = null): bool
		{
			return $this->delete($this->css,$context, $name = null);
		}
	}