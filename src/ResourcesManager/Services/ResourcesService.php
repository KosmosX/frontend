<?php

	namespace ResourcesManager\Services;

	use ResourcesManager\Services\AbstractServiceDOM;

	/**
	 * Method to get tag HTML of resources service
	 * dump()
	 * renderStyle()
	 * renderCss()
	 * renderScript()
	 * renderJs()
	 * renderVariable()
	 *
	 * Method to push resources
	 * style()
	 * css()
	 * script()
	 * js()
	 * variable()
	 *
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
		 * Il valore di ritorno sarà una stringa composta dai tag HTML (in base alla risorsa scelta)
		 *
		 * @param string      $resources
		 * @param null|string $get
		 *
		 * @return null|string
		 */
		public function dump(string $resources, ?string $get = null): ? string
		{
			$name = $this->checkName($get);
			$context = $this->checkContext($get);

			switch ($resources) {
				case 'script':
					return $this->renderScript($context, $name);
				case 'style':
					return $this->renderStyle($context, $name);
				case 'variable':
					return $this->renderVariable($context ?: $name);
				case 'js':
					return $this->renderJs($context, $name);
				case 'css':
					return $this->renderCss($context, $name);
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
		 * Render $style, stesso funzionamento di renderScript, solamente che renderizza file css interni o esterni al
		 * sistema
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
		 * Può essere utilizzato per creare varibili che contengo i valori da passare al frontend senza utilizzare il
		 * print di Laravel ovvero {{!! !!}} Possono essere create varibili con il nome di riferimento che conterranno
		 * i valore relativi a $this->varible[$name] Oppure creare un'unica variabile con il nome definito nel file
		 * .env (o quello di default JS_LARAVEL)
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
		 * Funzione che aggiunge codice js all'interno dell'array $js
		 * È obbligatorio passare il valore dello snippet js
		 * Non è necessario dichiarare un contesto (ovvero dove sarà posizionato all'interno del DOM) per default verrà inserito sotto la chiave 'body'
		 *
		 * Se si vuole aggiungere un contesto di caricamento della risorsa basterà indicarlo nel parametro $context (nel caso in cui la chiave scelta non è contenuta in
		 * self::CONTEXT o $this->context non verrà inserito nell'array e non saranno restituiti errori.
		 * Se si vuole assegnare anhce un nome alla risorsa per identificarla basterà passare il paramentro $context con dot notation, per esempio: 'body.name',
		 * 'footer.googleManager' etc.. in cui la prima parte è il contesto di caricamento e la seconda sarà il nome
		 *
		 * Il parametro $property sono tutte le proprietà che verranno assegnate alla risorsa, è un array associativo, con nome proprietà => valore_proprietà,
		 * esempio:
		 *  $this->js('console.log(true)', 'footer', array('ex'=>'value_ex'));
		 *  quando verrà renderizzata la risorsa sarà uguale a questo:
		 *  <script ex="value_ex">console.log(true)</script>
		 *
		 * @param string      $content
		 * @param null|string $context
		 * @param array  $property
		 *
		 * @return object
		 */
		public function js(string $content, ?string $context = 'body', array $property = null): object
		{
			$property = $this->property($property, true);

			$this->push($this->js, 'script', $context, $property, $content);
			return $this;
		}

		/**
		 * Funzione che aggiunge codice css all'interno dell'array $css
		 * Stesso procedimento della funzione js()
		 *
		 * @param string      $content
		 * @param null|string $context
		 * @param array|null  $property
		 *
		 * @return object
		 */
		public function css(string $content, ?string $context = 'body', array $property = null): object
		{
			$property = $this->property($property, true);

			$this->push($this->css, 'style', $context, $property, $content);
			return $this;
		}

		/**
		 * Funzione che aggiunge script javascript all'interno dell'array $scripts
		 * Stesso procedimento della funzione js()
		 * Il parametro $asset serve per dichiarare se l'url dello script è interno al sistema o esterno
		 *
		 * @param \ResourcesManager\Services\string      $script
		 * @param null|\ResourcesManager\Services\string $context
		 * @param \ResourcesManager\Services\bool        $asset
		 *
		 * @return object
		 */
		public function script(string $url, ?string $context = 'body', array $property = null): object
		{
			$property = array_merge($property, array("src" => $url)); //merge $property with url of script
			$property = $this->property($property); //create string of property

			$this->push($this->scripts, 'script', $context, $property);

			return $this;
		}

		/**
		 * Funzione che aggiunge fogli di stile css all'interno dell'array $style
		 * Stesso procedimento della funzione js()
		 * Il parametro $asset serve per dichiarare se l'url del file di stile è interno al sistema o esterno
		 *
		 * @param \ResourcesManager\Services\string      $style
		 * @param null|\ResourcesManager\Services\string $context
		 * @param \ResourcesManager\Services\bool        $asset
		 *
		 * @return $this
		 */
		public function style(string $url, ?string $context = 'body', array $property = null)
		{
			$property = array_merge($property, array("rel" => "stylesheet", "href" => $url));
			$property = $this->property($property);

			$this->push($this->style, 'link', $context, $property);

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
	}