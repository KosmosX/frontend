<?php

	namespace Kosmosx\Frontend\Services\Interfaces;

	interface JsVarsInterface extends FrontendServiceInterface
	{
		function add($variable, string $name = null): FrontendServiceInterface;

	}