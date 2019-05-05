<?php

	namespace Kosmosx\Frontend\Commands;

	use Kosmosx\Frontend\Commands\Commands;
	use Kosmosx\Frontend\Commands\CommandsInterface;

	class ForgetCommand extends Commands implements CommandsInterface
	{
		public function execute(): ?string
		{
			$args = func_get_args();
			return $this->processor->delete(...$args);
		}
	}