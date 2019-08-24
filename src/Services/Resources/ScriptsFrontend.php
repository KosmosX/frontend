<?php

	namespace Kosmosx\Frontend\Services\Resources;

	use Kosmosx\Frontend\Services\FrontendService;
	use Kosmosx\Frontend\Services\Interfaces\FrontendServiceInterface;
	use Kosmosx\Frontend\Services\Interfaces\ResourcesInterface;

	class ScriptsFrontend extends FrontendService implements ResourcesInterface
	{
		protected $scripts = array();

		public function dump(?string $get = null): ?string
		{
			return $this->rendering($this->scripts, $get);
		}

		public function add(string $url, array $property = array(), string $put = 'body'): FrontendServiceInterface
		{
			$property = array_merge($property, array("src" => $url)); //merge $property with url of script
			$property = $this->property($property); 				  //create string of property

			$this->push($this->scripts, 'script', $put, $property);

			return $this;
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function has(string $context, ?string $name = null): bool
		{
			return $this->exist($this->scripts,$context, $name = null);
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function forget(string $context, ?string $name = null): bool
		{
			return $this->delete($this->scripts,$context, $name = null);
		}
	}