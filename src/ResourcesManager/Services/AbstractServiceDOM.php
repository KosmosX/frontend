<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 03/01/19
	 * Time: 15.18
	 */

	namespace ResourcesManager\Services;

	abstract class AbstractServiceDOM
	{
		const CONTEXT = array(
			'head',
			'body',
			'footer',
			'defer',
			'async',
		);

		protected $template = "<{{tag}} {{property}}>{{content}}</{{tag}}>";

		protected $context = array();

		abstract public function load(string $get, ?string $context = null, ?string $name = null);

		/**
		 * Clean attr
		 *
		 * @param null|string $attr
		 *
		 * @return $this
		 */
		public function init(?string $attr = null)
		{
			if (null != $attr && property_exists(get_class($this), $attr))
				$this->{$attr} = array();
			else {
				$attr = get_class_vars(get_class($this));

				foreach (array_keys($attr) as $name) {
					if ($name !== 'template')
						$this->{$name} = array();
				}
			}
			return $this;
		}

		/**
		 * Remove all tags with the given name
		 *
		 * @param string      $type
		 * @param null|string $name
		 *
		 * @return $this
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
		 * If exist meta or og
		 *
		 * @param string      $type
		 * @param null|string $name
		 *
		 * @return bool
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
		 * @param string   $text
		 * @param int|null $maxLength
		 *
		 * @return null|string
		 */
		public function cleanText(?string $text, ?int $maxLength = 250): ?string
		{
			if (null === $text)
				return '';

			$text = trim(strip_tags($text));
			$text = preg_replace("/\r|\n/", '', $text);

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
		 * Aggiunge un elemento al rispettivo attributo
		 *
		 * @param string      $context
		 * @param null|string $name
		 * @param null|string $property
		 *
		 * @return $this
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
		 * @param null|string $value
		 *
		 * @return null|string
		 */
		protected function checkName(?string $value): ?string
		{
			if (false !== strstr($value, '.')) {
				[$context, $name] = explode('.', $value);
				return $name;
			}
			return null;
		}

		/**
		 * @param null|string $context
		 * @param array       $rules
		 * @param string      $default
		 *
		 * @return string
		 */
		protected function checkContext(?string $context, string $default = ''): string
		{

			$name = $this->checkName($context);

			if (null != $name)
				$context = str_replace("." . $name, '', $context);

			$rules = array_merge(self::CONTEXT, $this->context);
			if (null == $context || false === in_array($context, $rules))
				return in_array($default, $rules) ? $default : 'body';

			return $context;
		}

		/**
		 * Function to process the data of tags and give html output
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
		 * @param null|string $context
		 * @param string      $name
		 *
		 * @return null|string
		 */
		protected function rendering(array $attr, ?string $context = null, ?string $name = null): ?string
		{
			$render = null;

			if (null == $context) {
				$render = array_flatten($attr);
			} else if (array_key_exists($context, $attr)) {
				if (null == $name)
					$render = $attr[$context];
				if (array_key_exists($name, $attr[$context]))
					$render = (array)$attr[$context][$name];
			}

			return $render ? implode(PHP_EOL, $render) : null;
		}

		/**
		 * @param array $property
		 *
		 * @return null|string
		 */
		protected function property(array $property, bool $withNull = true): ?string
		{
			$output = '';
			foreach ($property as $key => $value) {
				if (null != $value || true === $withNull)
					$output .= $key . (is_string($value) ? "=\"" . $value . "\"" : null) . " ";
			}
			return $output;
		}
	}