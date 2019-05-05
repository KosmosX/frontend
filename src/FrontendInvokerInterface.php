<?php

	namespace Kosmosx\Frontend;

	use Kosmosx\Frontend\Services\FrontendProcessorInterface;

	interface FrontendInvokerInterface
	{
		function add(): FrontendInvokerInterface;
		function dump(): ?string;
		function forget(): bool;
		function has(): bool;
		function getIinstance(): FrontendProcessorInterface;
		function setInstance(FrontendProcessorInterface $processor): FrontendInvokerInterface;
	}