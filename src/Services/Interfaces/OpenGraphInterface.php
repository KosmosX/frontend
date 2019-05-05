<?php

	namespace Kosmosx\Frontend\Services\Interfaces;

	interface OpenGraphInterface extends FrontendServiceInterface
	{
		function add(string $name, ?string $value, ?string $prefix = null): FrontendServiceInterface;
	}