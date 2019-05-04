<?php

namespace Kosmosx\Frontend\Services\Interfaces;

interface FrontendInterface
{
    function dump(string $resources, ?string $get = null): ? string;
}