<?php

	namespace Kosmosx\Frontend\Services\Metatags;

	use Kosmosx\Frontend\Services\FrontendProcessor;
	use Kosmosx\Frontend\Services\FrontendProcessorInterface;

	class MetatagsFrontend extends FrontendProcessor implements FrontendProcessorInterface
	{
		protected $metatags = array();

		public function dump(?string $get = null): ?string
		{
			return $this->rendering($this->metatags, $get);
		}

		public function add(string $name, ?string $value): object
		{
			$value = $this->cleanText($value);

			$property = $this->property(array("name" => $name, "content" => $value));
			$this->push($this->metatags, 'meta', 'head.' . $name, $property, null);

			return $this;
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function has(string $context, ?string $name = null): bool
		{
			return $this->exist($this->metatags,$context, $name = null);
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function forget(string $context, ?string $name = null): bool
		{
			return $this->delete($this->metatags,$context, $name = null);
		}
	}