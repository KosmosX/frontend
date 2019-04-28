<?php

	namespace Kosmosx\Frontend\Services;

	use Kosmosx\Frontend\Services\Abstracts\ServiceProcessor;
    use Kosmosx\Frontend\Services\Interfaces\FrontManagerInterface;

    /**
	 * Method to get tag HTML of metatag service
	 * dump()
	 * renderMeta()
	 * renderOg()
	 * renderExtra()
	 *
	 * Method to push metatag and make tag HTML
	 * meta()
	 * og()
	 * twitter()
	 * extra()
	 *
	 * Class HtmlTagService
	 * @package App\Serivces
	 */
	class MetatagService extends ServiceProcessor implements FrontManagerInterface
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
		 *                      nome del tag da recuperare
		 * @return null|string
		 */
		public function dump(string $resources, ?string $get = null): ? string
		{
			$get = 'head.' . $get; //Metatag service only head context

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
		 * Get meta tags, return string of HTML code.
		 * If you get a specific metatags use $get
		 *
		 * @param null|string $get
		 *                      name of propriety (ex: 'description', 'key')
		 * @return null|string
		 */
		protected function renderMeta(?string $get = null): ?string
		{
			return $this->rendering($this->meta, $get);
		}

		/**
		 * Get open graph tags, return HTML code.
		 * If you get a specific og use $get
		 *
		 * @param null|string $get
		 *                      name of og tag (ex: 'og:title', 'og:image:alt', 'twitter:description')
		 * @return null|string
		 */
		protected function renderOg(?string $get = null): ?string
		{
			return $this->rendering($this->og, $get);
		}

		/**
		 * Get extra tags, return HTML code.
		 * If you get a specific extra use $get
		 *
		 * @param null|string $get
		 *
		 * @return null|string
		 */
		protected function renderExtra(?string $get = null): ?string
		{
			return $this->rendering($this->extra, $get);
		}

		/**
		 * Get all tags, return HTML code.
		 *
		 * @return string
		 */
		protected function renderAll(): string
		{
			$tags = $this->renderExtra() . $this->renderMeta() . $this->renderOg();
			return $tags;
		}

		/**
		 * Add tag to meta.
		 * Context is always 'head'
		 *
		 * @param string      $name
		 *                         name of propriety (ex: 'description')
		 * @param null|string $value
		 *                          value of propriety (ex 'Package for...')
		 *
		 * @return \Kosmosx\Frontend\Services\MetatagService
		 */
		public function meta(string $name, ?string $value): MetatagService
		{
			$value = $this->cleanText($value);

			$property = $this->property(array("name" => $name, "content" => $value));
			$this->push($this->meta, 'meta', 'head.' . $name, $property, null);

			return $this;
		}

		/**
		 * Add og twitter to opengraph array.
		 * Context is always 'head'
		 *
		 * @param string      $name
		 * @param null|string $value
		 *
		 * @return \Kosmosx\Frontend\Services\MetatagService
		 */
		public function twitter(string $name, ?string $value): MetatagService
		{
			$this->og($name, $value, 'twitter');
			return $this;
		}

		/**
		 * Add tag og to opengraph array
		 * Context is always 'head'
		 *
		 * @param string      $name
		 * @param null|string $value
		 * @param null|string $prefix
		 *
		 * @return \Kosmosx\Frontend\Services\MetatagService
		 */
		public function og(string $name, ?string $value, ?string $prefix = null): MetatagService
		{
			$value = $this->cleanText($value);

			$name = ($prefix ? $prefix . ':' : self::PREFIX_OG) . $name;
			$property = $this->property(array("property" => $name, "content" => $value));
			$this->push($this->og, 'meta', 'head.' . $name, $property, null);

			return $this;
		}

		/**
		 * Add meta tag extra (charset, viewport etc..)
		 * Context is always 'head'
		 *
		 * @param string      $type
		 * @param string      $name
		 * @param null|string $value
		 *
		 * @return \Kosmosx\Frontend\Services\MetatagService
		 */
		public function extra(string $type, string $name, ?string $value): MetatagService
		{
			$value = $this->cleanText($value);

			$property = $this->property(array($type => $name, "content" => $value), false);
			$this->push($this->og, 'meta', 'head.' . $name, $property, null);

			return $this;
		}
	}