<?php

	namespace Kosmosx\Frontend\Commands;

	use Kosmosx\Frontend\Commands\Parent\Commands;
	use Kosmosx\Frontend\Commands\Parent\CommandsInterface;

	class AddCommand extends Commands implements CommandsInterface
	{
		public function execute(): ?string
		{
			$args = func_get_args();
			return $this->processor->add(...$args);
		}
	}