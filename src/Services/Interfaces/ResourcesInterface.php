<?php

	namespace Kosmosx\Frontend\Services\Interfaces;

	interface ResourcesInterface extends FrontendServiceInterface
	{
		function add(string $url, array $property = array(), string $put = 'body'): FrontendServiceInterface;
	}