<?php

	namespace Kosmosx\Frontend\Services;

	interface FrontendProcessorInterface
	{
		function forget(string $context, ?string $name = null): bool;
		
		function has(string $context, ?string $name = null): bool;
	}