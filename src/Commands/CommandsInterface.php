<?php

	namespace Kosmosx\Frontend\Commands;

	interface CommandsInterface
	{
		function execute(): ?string;
	}