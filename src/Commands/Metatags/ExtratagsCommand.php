<?php

	namespace Kosmosx\Frontend\Commands\Metatags;

	use Kosmosx\Frontend\Commands\CommandProcessor;
	use Kosmosx\Frontend\Commands\CommandsInterface;

	class ExtratagsCommand extends CommandProcessor implements CommandsInterface
	{
		protected $extratags = array();

		public function get(?string $get = null): ?string
		{
			return $this->rendering($this->extratags, $get);
		}

		public function add(string $type, string $name, ?string $value)
		{
			$value = $this->cleanText($value);

			$property = $this->property(array($type => $name, "content" => $value), false);
			$this->push($this->og, 'meta', 'head.' . $name, $property, null);

			return $this;
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function has(string $context, ?string $name = null): bool
		{
			return $this->exist($this->extratags,$context, $name = null);
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function forget(string $context, ?string $name = null): bool
		{
			return $this->delete($this->extratags,$context, $name = null);
		}
	}