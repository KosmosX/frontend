<?php

	namespace Kosmosx\Frontend\Services\Interfaces;

	interface FrontendServiceInterface
	{
		function dump(?string $get = null): ?string;

		function forget(string $context, ?string $name = null): bool;

		function has(string $context, ?string $name = null): bool;
	}