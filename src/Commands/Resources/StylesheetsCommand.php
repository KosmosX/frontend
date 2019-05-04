<?php

	namespace Kosmosx\Frontend\Commands\Resources;

	use Kosmosx\Frontend\Commands\CommandProcessor;
	use Kosmosx\Frontend\Commands\CommandsInterface;

	class StylesheetsCommand extends CommandProcessor implements CommandsInterface
	{
		protected $stylesheets = array();

		public function get(?string $get = null): ?string
		{
			return $this->rendering($this->stylesheets, $get);
		}

		public function add(string $url, array $property = array(), string $put = 'body'): object
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