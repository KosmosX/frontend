<?php

	namespace Kosmosx\Frontend\Services\Resources;

	use Kosmosx\Frontend\Services\FrontendProcessor;
	use Kosmosx\Frontend\Services\FrontendProcessorInterface;

	class ScriptsFrontend extends FrontendProcessor implements FrontendProcessorInterface
	{
		protected $scripts = array();

		public function dump(?string $get = null): ?string
		{
			return $this->rendering($this->scripts, $get);
		}

		public function add(string $url, array $property = array(), string $put = 'body'): FrontendProcessorInterface
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