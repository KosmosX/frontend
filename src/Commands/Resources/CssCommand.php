<?php

	namespace Kosmosx\Frontend\Commands\Resources;

	use Kosmosx\Frontend\Commands\CommandProcessor;
	use Kosmosx\Frontend\Commands\CommandsInterface;

	class CssCommand extends CommandProcessor implements CommandsInterface
	{
		protected $css = array();

		public function get(?string $get = null): ?string
		{
			return $this->rendering($this->css, $get);
		}

		public function add(string $url, array $property = array(), string $put = 'body'): object
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