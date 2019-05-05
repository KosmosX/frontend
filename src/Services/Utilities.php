<?php

	namespace Kosmosx\Frontend\Services;

	trait Utilities
	{
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
		protected function delete(string $attr, string $context, ?string $name = null): object
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
		protected function exist(string $attr, string $context, ?string $name = null): bool
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
		protected function checkContext(?string $context): ?string
		{
			$position = strpos($context, '.');
			if(false !== $position)
				$context = mb_substr($context, 0,$position);

			$rules = array_merge(self::CONTEXT, $this->context);

			if (null == $context || false === in_array($context, $rules))
				return null;

			return $context;
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
		protected function cleanText(?string $text, ?int $maxLength = null): ?string
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
	}