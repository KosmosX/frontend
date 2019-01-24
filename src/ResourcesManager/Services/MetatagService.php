<?php

	namespace ResourcesManager\Services;

	use ResourcesManager\Utility\ResourceProcessor;

	/**
	 * Method to get tag HTML of metatag service
	 * dump()
	 * renderMeta()
	 * renderOg()
	 * renderExtra()
	 *
	 * Method to push resources and make tag HTML
	 * meta()
	 * og()
	 * twitter()
	 * extra()
	 *
	 * Class HtmlTagService
	 * @package App\Serivces
	 */
	class MetatagService extends ResourceProcessor
	{
		const PREFIX_OG = 'og:';

		protected $extra;

		protected $meta;

		protected $og;

		public function __construct()
		{
			$this->init();
		}

		/**
		 * Funzione che permette di renderizzare una risorsa specifica, tra:
		 * meta, og, extra, all
		 * Il valore di ritorno sarà una stringa composta dai tag HTML (in base alla risorsa scelta)
		 *
		 * @param string      $resources
		 * @param null|string $get
		 *
		 * @return null|string
		 */
		public function dump(string $resources, ?string $get = null): ? string
		{
			$get = 'head.' . $get;

			switch ($resources) {
				case 'meta':
					return $this->renderMeta($get);
				case 'og':
					return $this->renderOg($get);
				case 'extra':
					return $this->renderExtra($get);
				case 'all':
					return $this->renderAll();
				default:
					return null;
			}
		}

		/**
		 * Rendering meta tag, output html to print in the DOM
		 *
		 * @return string
		 */
		public function renderMeta(?string $get = null): ?string
		{
			return $this->rendering($this->meta, $get);
		}

		/**
		 * Rendering open graph, output html to print in the DOM
		 *
		 * @return string
		 */
		public function renderOg(?string $get = null): ?string
		{
			return $this->rendering($this->og, $get);
		}

		/**
		 * Rendering extra tags, output html to print in the DOM
		 *
		 * @return string
		 */
		public function renderExtra(?string $get = null): ?string
		{
			return $this->rendering($this->extra, $get);
		}

		/**
		 * Rendering all tags, output html to print in the DOM
		 *
		 * @return string
		 */
		public function renderAll(): string
		{
			$tags = $this->renderExtra() . $this->renderMeta() . $this->renderOg();
			return $tags;
		}

		/**
		 * Add tag to meta
		 *
		 * @param string      $name
		 * @param null|string $value
		 *
		 * @return \ResourcesManager\Services\MetatagService
		 */
		public function meta(string $name, ?string $value): MetatagService
		{
			$value = $this->cleanText($value);

			$property = $this->property(array("name" => $name, "content" => $value));
			$this->push($this->meta, 'meta', 'head.' . $name, $property, null);

			return $this;
		}

		/**
		 * @param string      $name
		 * @param null|string $value
		 *
		 * @return \ResourcesManager\Services\MetatagService
		 */
		public function twitter(string $name, ?string $value): MetatagService
		{
			$this->og($name, $value, 'twitter');
			return $this;
		}

		/**
		 * Add tag to og
		 *
		 * @param string      $name
		 * @param null|string $value
		 * @param null|string $prefix
		 *
		 * @return \ResourcesManager\Services\MetatagService
		 */
		public function og(string $name, ?string $value, ?string $prefix = null): MetatagService
		{
			$value = $this->cleanText($value);

			$property = $this->property(array("property" => ($prefix ?: self::PREFIX_OG) . $name, "content" => $value));
			$this->push($this->og, 'meta', 'head.' . $name, $property, null);

			return $this;
		}

		/**
		 * Add meta tag extra with different type name
		 *
		 * @param string      $type
		 * @param string      $name
		 * @param null|string $value
		 *
		 * @return \ResourcesManager\Services\MetatagService
		 */
		public function extra(string $type, string $name, ?string $value): MetatagService
		{
			$value = $this->cleanText($value);

			$property = $this->property(array($type => $name, "content" => $value), false);
			$this->push($this->og, 'meta', 'head.' . $name, $property, null);

			return $this;
		}
	}