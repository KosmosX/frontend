<?php

	namespace Kosmosx\Frontend\Services\Metatags;

	use Kosmosx\Frontend\Services\FrontendService;
	use Kosmosx\Frontend\Services\Interfaces\FrontendServiceInterface;
	use Kosmosx\Frontend\Services\Interfaces\OpenGraphInterface;

	class OpenGraphFrontend extends FrontendService implements OpenGraphInterface
	{
		const PREFIX_OG = 'og:';
		protected $og = array();

		public function dump(?string $get = null): ?string
		{
			return $this->rendering($this->og, 'head.' . $get);
		}

		public function add(string $name, ?string $value, ?string $prefix = null): FrontendServiceInterface
		{
			$value = $this->cleanText($value);

			$name = ($prefix ? $prefix . ':' : self::PREFIX_OG) . $name;
			$property = $this->property(array(
				"property" => $name,
				"content" => $value
			));
			$this->push($this->og, 'meta', 'head.' . $name, $property, null);

			return $this;
		}

		/**
		 * Add og twitter to opengraph array.
		 * Context is always 'head'
		 *
		 * @param string $name
		 * @param null|string $value
		 *
		 * @return \Kosmosx\Frontend\Services\MetatagFrontend
		 */
		public function twitter(string $name, ?string $value)
		{
			$this->add($name, $value, 'twitter');
			return $this;
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function has(string $context, ?string $name = null): bool
		{
			return $this->exist($this->og, $context, $name = null);
		}

		/**
		 * @param string $context
		 * @param string|null $name
		 * @return bool
		 */
		public function forget(string $context, ?string $name = null): bool
		{
			return $this->delete($this->og, $context, $name = null);
		}
	}