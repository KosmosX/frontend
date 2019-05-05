<?php

	namespace Kosmosx\Frontend\Commands;

	use Kosmosx\Frontend\Services\FrontendProcessorInterface;

	class Commands
	{
		protected $processor;

		public function __construct(FrontendProcessorInterface &$processor) {
			$this->processor = $processor;
		}
	}