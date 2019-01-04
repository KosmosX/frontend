<?php

	namespace ResourcesManager\Services;

	use ResourcesManager\Services\AbstractServiceDOM;

	/**
	 * Class ResourceService
	 * @package App\Services
	 */
	class ResourcesService extends AbstractServiceDOM
	{
		protected $js;

		protected $variable;

		protected $css;

		protected $scripts;

		protected $style;

		public function __construct()
		{
			$this->init();
		}

		/**
		 * Funzione per caricare le risorse nelle view
		 *
		 * @param string      $get (recuperare uno degli attr)
		 * @param null|string $context
		 * @param string      $name
		 *
		 * @return null|string
		 */
		public function load(string $get, ?string $context = null, ?string $name = null): ? string
		{
			switch ($get) {
				case 'script':
					return $this->renderScript($context, $name);
				case 'style':
					return $this->renderStyle($context, $name);
				case 'variable':
					return $this->renderVariable($context ?: $name);
				case 'js':
					return $this->renderJs($context, $name);
				case 'css':
					return $this->renderCss();
				default:
					return null;
			}
		}

		/**
		 * Render $script, renderizza gli script restituendo i script tag da caricare nel DOM.
		 * Il primo parametro serve per recupere solo un tipo di contesto
		 * Il secondo per recuperare un solo script da un contesto specifico
		 *
		 * @param string|NULL $context
		 * @param string      $name
		 *
		 * @return null|string
		 */
		protected function renderScript(?string $context = null, ?string $name = null): ?string
		{

			return $this->rendering($this->scripts, $context, $name);
		}

		/**
		 * @param null|string $context
		 * @param string      $name
		 *
		 * @return null|string
		 */
		protected function renderStyle(?string $context = null, ?string $name = null): ?string
		{
			return $this->rendering($this->style, $context, $name);
		}

		/**
		 * Render $js attr, make a global js variable with all value of $js.
		 *
		 * @return null|string
		 */
		protected function renderVariable(?string $name = null): ?string
		{
			$output = null;

			if (null == $this->variable)
				return $output;

			$property = "type=\"text/javascript\"";

			if (null != $name && array_key_exists($name, $this->variable)) {
				$variable['name'] = $name;
				$variable['content'] = json_encode($this->variable[$name], JSON_FORCE_OBJECT);
			} else {
				$variable['name'] = env('JS_VARIABLE', 'JS_LARAVEL');
				$variable['content'] = json_encode($this->variable, JSON_FORCE_OBJECT);
			}

			$content = "var " . $variable['name'] . " = " . $variable['content'] . ";";
			unset($variable);

			$output = $this->templateProcessor('script', $property, $content);

			return $output;
		}

		/**
		 * @param null|string $context
		 * @param null|string $name
		 *
		 * @return null|string
		 */
		protected function renderJs(?string $context = null, ?string $name = null): ?string
		{
			return $this->rendering($this->js, $context, $name);
		}

		/**
		 * @param null|string $context
		 * @param null|string $name
		 *
		 * @return null|string
		 */
		protected function renderCss(?string $context = null, ?string $name = null): ?string
		{
			return $this->rendering($this->css, $context, $name);
		}

		/**
		 * Funzione che aggiunge snippet di codici
		 *
		 * @param             $value
		 * @param null|string $key
		 *
		 * @return $this
		 */
		public function js(string $content, ?string $context = 'body')
		{
			$this->push($this->js, 'script', $context, null, $content);
			return $this;
		}

		/**
		 * @param string      $content
		 * @param null|string $context
		 *
		 * @return $this
		 */
		public function css(string $content, ?string $context = 'body')
		{
			$this->push($this->css, 'style', $context, null, $content);
			return $this;
		}

		/**
		 * Funzione che aggiunge gli script che poi verranno caricati nella view
		 *
		 * @param string      $script  (url of script)
		 * @param null|string $context (context of script)
		 *                             Use DOT notation for add name to script, example 'footer.mainjs'
		 * @param bool|null   $asset
		 *
		 * @return $this
		 */
		public function script(string $script, ?string $context = 'body', bool $asset = true)
		{
			$uri = $asset ? asset($script) : $script;

			$property = $this->property(array("src" => $uri));
			if (false !== strstr($context, 'async'))
				$property .= "async";
			if (false !== strstr($context, 'defer'))
				$property .= "defer";

			$this->push($this->scripts, 'script', $context, $property);

			return $this;
		}

		/**
		 * @param             $variable
		 * @param string|null $name
		 *
		 * @return $this
		 */
		public function variable($variable, string $name = null)
		{
			if (is_array($variable) && $name == null)
				$this->variable = array_merge($this->variable, $variable);
			else
				$this->variable[$name] = $variable;

			return $this;
		}

		/**
		 * @param string      $style
		 * @param null|string $context
		 * @param bool        $asset
		 *
		 * @return $this
		 */
		public function style(string $style, ?string $context = 'body', bool $asset = true)
		{
			$uri = $asset ? asset($style) : $style;

			$property = $this->property(array("rel" => "stylesheet", "href" => $uri));

			$this->push($this->style, 'link', $context, $property);

			return $this;
		}
	}