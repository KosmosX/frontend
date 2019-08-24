<?php

	namespace Kosmosx\Frontend\Services;

	class FrontendService
	{
		use Utilities;
		
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
		const TEMPLATE = "<{{tag}} {{property}}>{{content}}</{{tag}}>";

		/**
		 * Template with self close
		 *
		 * @var string
		 */
		const TEMPLATE_SELF = "<{{tag}} {{property}} />";

		/**
		 * Context extra
		 *
		 * @var array
		 */
		protected $context = array();

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
		 * @param string      $put
		 *                            context and name of resources (dot notation)
		 * @param null|string $property
		 *                             properties of tag HTML
		 * @param null|string $content
		 *                            content to be written inside the tag
		 *
		 * @return object
		 */
		protected function push(array &$attr, string $tag, string $put, ?string $property = null, ?string $content = null): object
		{
			try {
				$context = $this->checkContext($put);
				$name = $this->checkName($put);

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
		 * Funzione che processa la risorsa e la fa diventare un tag HTML sostituendo i valori passati al template di default.
		 * Restituisce la stringa del tag appena creato
		 *
		 * @return string
		 */
		protected function templateProcessor(string $tag, ?string $property = null, ?string $content = null): string
		{
			$search = array('{{tag}}', '{{property}}', '{{content}}');
			$replace = array($tag, $property, $content);

			$output = str_replace($search, $replace, self::TEMPLATE);

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
	}