<?php

	namespace Kosmosx\Frontend;

	use Kosmosx\Frontend\Commands\AddCommand;
	use Kosmosx\Frontend\Commands\ForgetCommand;
	use Kosmosx\Frontend\Commands\HasCommand;
	use Kosmosx\Frontend\Commands\DumpCommand;
	use Kosmosx\Frontend\Services\FrontendProcessorInterface;

	class ProcessorInvoker implements FrontendInvokerInterface
	{
		protected $processor;

		public function __construct(FrontendProcessorInterface $processor)
		{
			$this->processor = $processor;
		}

		public function add(): FrontendInvokerInterface
		{
			$args = func_get_args();
			$rendering = new AddCommand($this->processor);
			$rendering->execute(...$args);
			return $this;
		}

		public function dump(): ?string
		{
			$args = func_get_args();
			$rendering = new DumpCommand($this->processor);
			return $rendering->execute(...$args);
		}

		public function forget(): bool
		{
			$args = func_get_args();
			$rendering = new ForgetCommand($this->processor);
			return $rendering->execute(...$args);
		}

		public function has(): bool
		{
			$args = func_get_args();
			$rendering = new HasCommand($this->processor);
			return $rendering->execute(...$args);
		}

		public function getIinstance(): FrontendProcessorInterface
		{
			return $this->processor;
		}

		public function setInstance(FrontendProcessorInterface $processor): FrontendInvokerInterface
		{
			$this->processor = $processor;
		}

		public function toArray(): array
		{
			return $this->processor->toArray();
		}
	}