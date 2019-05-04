<?php

namespace Kosmosx\Frontend\Factory;

use Kosmosx\Frontend\Factory\Interfaces\FactoryInterface;
use Kosmosx\Frontend\Services\MetatagFrontend;
use Kosmosx\Frontend\Services\ResourcesFrontend;

class FrontendFactory implements FactoryInterface
{

    public function metatags()
    {
        return new MetatagFrontend();
    }

    public function resources()
    {
        return new ResourcesFrontend();
    }
}