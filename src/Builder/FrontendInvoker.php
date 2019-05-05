<?php

	namespace Kosmosx\Frontend\Builder;

	use Kosmosx\Frontend\Commands\AddCommand;
	use Kosmosx\Frontend\Commands\ForgetCommand;
	use Kosmosx\Frontend\Commands\HasCommand;
	use Kosmosx\Frontend\Commands\DumpCommand;
	use Kosmosx\Frontend\Services\FrontendServiceInterface;

	class FrontendInvoker implements FrontendInvokerInterface
	{
		protected $service;

		private $add;
		private $dump;
		private $has;
		private $forget;

		public function __construct(FrontendServiceInterface $frontendService)
		{
			$this->service = $frontendService;

			$this->add = new AddCommand($this->service);
			$this->dump = new DumpCommand($this->service);
			$this->forget = new ForgetCommand($this->service);
			$this->has = new HasCommand($this->service);
		}

		public function add(): FrontendInvokerInterface
		{
			$args = func_get_args();
			$this->add->execute(...$args);
			return $this;
		}

		public function dump(): ?string
		{
			$args = func_get_args();
			return $this->dump->execute(...$args);
		}

		public function forget(): bool
		{
			$args = func_get_args();
			return $this->forget->execute(...$args);
		}

		public function has(): bool
		{
			$args = func_get_args();
			return $this->has->execute(...$args);
		}

		public function getIinstance(): FrontendServiceInterface
		{
			return $this->service;
		}

		public function toArray(): array
		{
			return $this->service->toArray();
		}
	}