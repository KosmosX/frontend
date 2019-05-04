<?php

	namespace Kosmosx\Frontend\Commands\Resources;

	use Kosmosx\Frontend\Commands\CommandProcessor;
	use Kosmosx\Frontend\Commands\CommandsInterface;

	class ScriptsCommand extends CommandProcessor implements CommandsInterface
	{
		protected $scripts = array();

		public function get(?string $get = null): ?string
		{
			return $this->rendering($this->scripts, $get);
		}

		public function add(string $url, array $property = array(), string $put = 'body'): object
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