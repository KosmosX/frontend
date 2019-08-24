<?php

	namespace Kosmosx\Frontend\Services\Interfaces;

	interface ExtratagsInterface extends FrontendServiceInterface
	{
		function add(string $type, string $name, ?string $value): FrontendServiceInterface;
	}