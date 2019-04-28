<?php

namespace Kosmosx\Frontend\Services\Interfaces;

interface FrontManagerInterface
{
    function dump(string $resources, ?string $get = null): ? string;
}