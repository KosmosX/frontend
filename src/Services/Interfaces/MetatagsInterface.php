<?php

	namespace Kosmosx\Frontend\Services\Interfaces;

	interface MetatagsInterface extends FrontendServiceInterface
	{
		function add(string $name, ?string $value): FrontendServiceInterface;
	}