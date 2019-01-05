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
		 * Funzione che permette di renderizzare una risorsa specifica, tra:
		 * script, style, css, js, variable
		 * Il valore di ritorno sarà una stringa composta dai tag HTML (in base alla risorsa scleta) da inserire nella view o da restituire in una risposta
		 *
		 * @param \ResourcesManager\Services\string      $get
		 * @param null|\ResourcesManager\Services\string $context
		 * @param null|\ResourcesManager\Services\string $name
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
		 * Render $script, renderizza gli script restituendo una stringa composta tag HTML da inserire nel DOM.
		 * Deve essere utilizzata per caricare gli script javascript interni o esterni al sistema.
		 * Il primo parametro serve per recupere i gli script renderizzati (cioè tag html) di uno specifico contesto
		 * Il secondo per recuperare un solo script renderizzato da un contesto specifico
		 *
		 * @param null|\ResourcesManager\Services\string $context
		 * @param null|\ResourcesManager\Services\string $name
		 *
		 * @return null|string
		 */
		protected function renderScript(?string $context = null, ?string $name = null): ?string
		{

			return $this->rendering($this->scripts, $context, $name);
		}

		/**
		 * Render $style, stesso funzionamento di renderScript, solamente che renderizza file css interni o esterni al sistema
		 *
		 * @param null|\ResourcesManager\Services\string $context
		 * @param null|\ResourcesManager\Services\string $name
		 *
		 * @return null|string
		 */
		protected function renderStyle(?string $context = null, ?string $name = null): ?string
		{
			return $this->rendering($this->style, $context, $name);
		}

		/**
		 * Render $js, permette di creare varibili javascript all'interno del DOM.
		 * Può essere utilizzato per creare varibili che contengo i valori da passare al frontend senza utilizzare il print di Laravel ovvero {{!! !!}}
		 * Possono essere create varibili con il nome di riferimento che conterranno i valore relativi a $this->varible[$name]
		 * Oppure creare un'unica variabile con il nome definito nel file .env (o quello di default JS_LARAVEL)
		 *
		 * esempio:
		 * <script type="text/javascript"> var JS_LARAVEL = $this->variable* </script>
		 * <script type="text/javascript"> var objMenu = $this->variable['objMenu']* </script>
		 * *ovviamente saranno codificati per poter essere utilizzati poi dal js
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
		 * Render $js, stesso funzionamento di renderScript, solamente che renderizza snippet di codice js
		 *
		 * @param null|\ResourcesManager\Services\string $context
		 * @param null|\ResourcesManager\Services\string $name
		 *
		 * @return null|string
		 */
		protected function renderJs(?string $context = null, ?string $name = null): ?string
		{
			return $this->rendering($this->js, $context, $name);
		}

		/**
		 * Render $css, stesso funzionamento di renderScript, solamente che renderizza snippet di codice css
		 *
		 * @param null|\ResourcesManager\Services\string $context
		 * @param null|\ResourcesManager\Services\string $name
		 *
		 * @return null|string
		 */
		protected function renderCss(?string $context = null, ?string $name = null): ?string
		{
			return $this->rendering($this->css, $context, $name);
		}

		/**
		 * Funzione che aggiunge le codice js all'interno dell'array $js
		 * È obbligatorio passare il valore dello snippet js
		 * Non è necessario dichiarare un contesto (ovvero dove sarà posizionato all'interno del DOM, oopure, custom) o il nome
		 * relativo allo snippet inserito.
		 *
		 * Tuttavia se si vuole aggiungere un contesto basterà passare il $context (se il valore non è contenuto in CONTEXT o $this->context non verrà inserito nell'array e non sarabbi restituiti errori
		 * Se si vuole assegnare anhce un nome a ciò che verrà inserito basterà passare il paramentro $context per esempio: 'body.name', 'footer.googleManager' etc..
		 *
		 * @param \ResourcesManager\Services\string      $content
		 * @param null|\ResourcesManager\Services\string $context
		 *
		 * @return object
		 */
		public function js(string $content, ?string $context = 'body'): object
		{
			$this->push($this->js, 'script', $context, null, $content);
			return $this;
		}

		/**
		 * Funzione che aggiunge codice css all'interno dell'array $css
		 * Stesso procedimento della funzione js()
		 *
		 * @param \ResourcesManager\Services\string      $content
		 * @param null|\ResourcesManager\Services\string $context
		 *
		 * @return object
		 */
		public function css(string $content, ?string $context = 'body'): object
		{
			$this->push($this->css, 'style', $context, null, $content);
			return $this;
		}

		/**
		 * Funzione che aggiunge script all'interno dell'array $scripts
		 * Stesso procedimento della funzione js()
		 * Il parametro $asset serve per dichiarare se l'url dello script è interno al sistema o esterno
		 *
		 * @param \ResourcesManager\Services\string      $script
		 * @param null|\ResourcesManager\Services\string $context
		 * @param \ResourcesManager\Services\bool        $asset
		 *
		 * @return object
		 */
		public function script(string $script, ?string $context = 'body', bool $asset = true): object
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
		 * Funzione per inserire varibili all'interno dell'array: $variable
		 * È necessario aggiungere il valore della varibile ed eventualmente il nome rifereto alla varibile
		 *
		 * @param                                        $variable
		 * @param \ResourcesManager\Services\string|null $name
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
		 * Funzione che aggiunge script all'interno dell'array $style
		 * Stesso procedimento della funzione js()
		 * Il parametro $asset serve per dichiarare se l'url del file di stile è interno al sistema o esterno
		 *
		 * @param \ResourcesManager\Services\string      $style
		 * @param null|\ResourcesManager\Services\string $context
		 * @param \ResourcesManager\Services\bool        $asset
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