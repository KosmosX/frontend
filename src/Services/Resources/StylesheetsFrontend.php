<?php

	namespace Kosmosx\Frontend\Services\Resources;

	use Kosmosx\Frontend\Services\FrontendService;
	use Kosmosx\Frontend\Services\Interfaces\ResourcesInterface;

	class StylesheetsFrontend extends FrontendService implements ResourcesInterface
	{
		protected $stylesheets = array();

		public function dump(?string $get = null): ?string
		{
			return $this->rendering($this->stylesheets, $get);
		}

		public function add(string $url, array $property = array(), string $put = 'body'): FrontendServiceInterface
		{
			$property = array_merge($property, array("rel" => "stylesheet", "href" => $url));
			$property = $this->property($property);

			$this->push($this->stylesheets, 'link', $put, $property);

			return $this;
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function has(string $context, ?string $name = null): bool
		{
			return $this->exist($this->stylesheets,$context, $name = null);
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function forget(string $context, ?string $name = null): bool
		{
			return $this->delete($this->stylesheets,$context, $name = null);
		}
	}