<?php

	namespace Kosmosx\Frontend\Commands;

	use Kosmosx\Frontend\Commands\Parent\Commands;
	use Kosmosx\Frontend\Commands\Parent\CommandsInterface;

	class DumpCommand extends Commands implements CommandsInterface
	{
		public function execute(): ?string
		{
			$args = func_get_args();
			return $this->processor->dump(...$args);
		}
	}