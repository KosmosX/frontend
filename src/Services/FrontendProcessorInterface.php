<?php

	namespace Kosmosx\Frontend\Services;

	interface FrontendProcessorInterface
	{
		//function add(string $url, array $property = array(), string $put = 'body'): FrontendProcessorInterface;

		//function dump(?string $get = null): ?string;

		function forget(string $context, ?string $name = null): bool;

		function has(string $context, ?string $name = null): bool;
	}