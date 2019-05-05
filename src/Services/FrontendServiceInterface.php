<?php

	namespace Kosmosx\Frontend\Services;

	interface FrontendServiceInterface
	{
		//function add(string $url, array $property = array(), string $put = 'body'): FrontendServiceInterface;

		//function dump(?string $get = null): ?string;

		function forget(string $context, ?string $name = null): bool;

		function has(string $context, ?string $name = null): bool;
	}