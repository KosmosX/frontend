<?php

	namespace Kosmosx\Frontend\Commands;

	interface CommandsInterface
	{
		function forget(string $context, ?string $name = null): bool;
		
		function has(string $context, ?string $name = null): bool;
	}