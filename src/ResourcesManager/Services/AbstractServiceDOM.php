<?php

	namespace ResourcesManager\Services;

	/**
	 * Class AbstractServiceDOM
	 * @package ResourcesManager\Services
	 */
	abstract class AbstractServiceDOM
	{
		/**
		 * Defautl context
		 */
		const CONTEXT = array(
			'head',
			'body',
			'footer',
			'defer',
			'async',
		);

		/**
		 * Default template
		 *
		 * @var string
		 */
		protected $template = "<{{tag}} {{property}}>{{content}}</{{tag}}>";

		/**
		 * Template with self close
		 *
		 * @var string
		 */
		protected $templateSelf = "<{{tag}} {{property}} />";

		/**
		 * Context extra
		 *
		 * @var array
		 */
		protected $context = array();

		/**
		 * Funzione per il recupero dei dati che verrano utilizzati dal DOM
		 *
		 * @param string      $resources
		 * @param null|string $get
		 *
		 * @return mixed
		 */
		abstract public function dump(string $resources, ?string $get = null);

		/**
		 * Funzione per rimuovere le risorse caricate.
		 * È necessario specificare il tipo di risorsa presente come attributo della classe, successivamente bisogna
		 * indicare il contesto che si vuole rimuovere.
		 * Se si vuole eliminare una specifica risorsa attraverso il nome che le è stato assegnato durante il
		 * caricamento basta passare oltre al $context anche il $name
		 *
		 * @param string      $attr
		 * @param string      $context
		 * @param null|string $name
		 *
		 * @return object
		 */
		public function forget(string $attr, string $context, ?string $name = null): object
		{
			if ($this->has($attr, $context, $name)) {
				if (null != $name)
					unset($this->{$attr}[$context][$name]);
				else
					unset($this->{$attr}[$context]);
			}

			return $this;
		}

		/**
		 * Controlla se sono presenti delle risorse all'interno di un contesto;
		 * Oppure se esiste una determinata risorsa all'interno di un contesto
		 *
		 * @param string      $attr
		 *                         name of attribute that contain resources
		 * @param string      $context
		 * @param null|string $name
		 *
		 * @return bool
		 *             true if exist
		 */
		public function has(string $attr, string $context, ?string $name = null): bool
		{
			if (false === property_exists(get_class($this), $attr))
				return false;

			if (null == $name && array_key_exists($context, $this->{$attr})) {
				return true;
			}
			foreach (array_values($this->{$attr}) as $tag) {
				foreach (array_keys($tag) as $value)
					if ($value === $name)
						return true;
			}

			return false;
		}

		/**
		 * Ripulisce il codice dai tag HTML e dai spazi bianchi in più, tagliando la lunghezza del testo, e sostituisce
		 * i caratteri speciali
		 *
		 * @param string   $text
		 * @param int|null $maxLength
		 *
		 * @return null|string
		 */
		public function cleanText(?string $text, ?int $maxLength = null): ?string
		{
			if (null == $text)
				return '';

			$text = trim(strip_tags($text)); //remove space and tags html
			$text = preg_replace("/\r|\n/", '', $text);

			//remove charter if text is > of maxLength and add '...'
			if (null != $maxLength) {
				$length = mb_strlen($text);
				$text = mb_substr($text, 0, $maxLength);

				if (mb_strlen($text) < $length)
					$text .= '...';
			}

			return $text;
		}

		/**
		 * @return string
		 */
		public function __toString(): string
		{
			return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
		}

		/**
		 * @return array
		 */
		public function toArray(): array
		{
			$toArray = array();

			$attr = get_class_vars(get_class($this));
			foreach (array_keys($attr) as $name) {
				array_set($toArray, $name, $this->{$name});
			}

			return $toArray;
		}

		/**
		 * Recupera i contesti disponibili
		 *
		 * @param bool $withDefault
		 *
		 * @return array
		 */
		public function getContext(bool $withDefault = true): array
		{
			return $withDefault ? array_merge(self::CONTEXT, $this->context) : $this->context;
		}

		/**
		 * Setta i contesti extra
		 *
		 * @param array $contexts
		 * @param bool  $override
		 *
		 * @return array
		 */
		public function setContext(array $contexts, bool $override = false): array
		{
			if ($override)
				$this->context = array_merge($this->context, $contexts);
			else
				$this->context = $contexts;

			return $this->context;
		}

		/**
		 * Inizializzazione le varibili della classe che verrà estesa
		 *
		 * @param null|string $attr
		 *
		 * @return $this
		 */
		protected function init(?string $attr = null)
		{
			if (null != $attr && property_exists(get_class($this), $attr))
				$this->{$attr} = array();
			else {
				$attr = get_class_vars(get_class($this));

				foreach (array_keys($attr) as $name) {
					if ($name !== 'template' && $name !== 'context')
						$this->{$name} = array();
				}
			}
			return $this;
		}

		/**
		 * Aggiunge un elemento all'array passato (un attributo di classe)
		 * Utilizzato dalla classi che estendono questa, per poter caricare all'interno degli attributi i valori delle risorse.
		 * Le risorse inserite devono:
		 * -specificare l'attirbuto su cui inserire i dati
		 * -specificare il nome del tag HTML
		 * -specificare il contesto e il nome(non obbligatorio) utilizzabile la dot notation per inserire contesto e nome risorsa 'body.nameResource'
		 * -è possibile passare le proprietà da inserire nel tag
		 * -è possibile passere il contenuto che verrà inserito all'interno del tag
		 *
		 * desiderati
		 *
		 * @param array       $attr
		 * @param string      $tag
		 *                        name of tag HTML
		 * @param string      $context
		 *                            context and name of resources (dot notation)
		 * @param null|string $property
		 *                             properties of tag HTML
		 * @param null|string $content
		 *                            content to be written inside the tag
		 *
		 * @return object
		 */
		protected function push(array &$attr, string $tag, string $context, ?string $property = null, ?string $content = null): object
		{
			try {
				$name = $this->checkName($context);
				$context = $this->checkContext($context);

				$output = $this->templateProcessor($tag, $property, $content);
				if (null != $name)
					$attr[$context][$name] = $output;
				else
					$attr[$context][] = $output;
				return $this;
			} catch (Exception $e) {
				return $this;
			}
		}

		/**
		 * Recupera
		 *
		 * esempio:
		 * $value = 'body'; return null
		 * $value = 'body.menu'; return 'menu'
		 *
		 * @param null|string $value
		 *
		 * @return null|string
		 */
		protected function checkName(?string $name): ?string
		{
			$position = strpos($name, '.');
			if(false === $position)
				return null;

			$name = mb_substr($name, $position + 1);

			return $name;
		}

		/**
		 * Resitusce il contesto che gli viene passato rimuovendo il nome e verificando se esiste all'interno dei contesti di default o extra.
		 * Se non esiste viene assegnato in automatico il contesto 'body' oppure quello specificato come default(anch'esso deve essre presente nei contesti)
		 *
		 * esempio:
		 * $value = 'body'; return 'body'
		 * $value = 'body.menu'; return 'body'
		 *
		 * @param null|string $context
		 * @param array       $rules
		 * @param string      $default
		 *
		 * @return string
		 */
		protected function checkContext(?string $context, string $default = ''): string
		{
			$position = strpos($context, '.');
			if(false !== $position)
				$context = mb_substr($context, 0,$position);

			$rules = array_merge(self::CONTEXT, $this->context);

			if (null == $context || false === in_array($context, $rules))
				return in_array($default, $rules) ? $default : 'body';

			return $context;
		}

		/**
		 * Funzione che processa la risorsa e la fa diventare un tag HTML sostituendo i valori passati al template di default.
		 * Restituisce la stringa del tag appena creato
		 *
		 * @return string
		 */
		protected function templateProcessor(string $tag, ?string $property = null, ?string $content = null): string
		{
			$search = array('{{tag}}', '{{property}}', '{{content}}');
			$replace = array($tag, $property, $content);

			$output = str_replace($search, $replace, $this->template);

			return $output;
		}

		/**
		 * Funzione utilizzata per renderizzare le risorse presenti all'interno dell'attributo passato.
		 * Restituisce in le risorse che sono state caricate come tag HTML come una singola stringa così da poterla utilizzare nel DOM o mandare nelle risposte text/html;
		 * La stringa prodotta potrà contenere tutte le risorse di uno specifico contesto oppure una specifica risorsa di un determinato contesto utilizzato il nome assegnatogli
		 *
		 * @param null|string $context
		 * @param string      $name
		 *
		 * @return null|string
		 */
		protected function rendering(array $attr, ?string $get = null): ?string
		{
			$render = null;

			$context = $this->checkContext($get);
			$name = $this->checkName($get);

			if (null == $context)
				$render = array_flatten($attr);

			if (array_key_exists($context, $attr)) {
				if (null == $name)
					$render = $attr[$context];
				if (array_key_exists($name, $attr[$context]))
					$render = (array)$attr[$context][$name];
			}

			return $render ? implode(PHP_EOL, $render) : null;
		}

		/**
		 * Funzione per generare la stringa che verrà inserita nelle proprietà dei tag HTML
		 * L'array proprietà è un array associativo key => value dove la chiave è il nome della proprietà e value è il valore che gli vuoi assegnare
		 * Il parametro $withNull se settato a true aggiungerà la key anche se è null
		 * se settato su false non aggiungerà il valore ( key )
		 *
		 * Example:
		 * $property = array("key"=>"value", "key2"=> null,"keyN"=>"valueN")
		 *  return 'key="value" key2 keyN="valueN"'
		 * @param array $property
		 *
		 * @return null|string
		 */
		protected function property(array $property, bool $withNull = true): ?string
		{
			$output = '';
			foreach ($property as $key => $value) {
				if (null != $value || true === $withNull)
					$output .= $key . (is_string($value) ? "=\"" . $value . "\"" : null) . (next($property) ? " " : null);
			}
			return $output;
		}
	}