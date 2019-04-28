<?php

namespace Kosmosx\Frontend\Factory;

use Kosmosx\Frontend\Factory\Interfaces\FactoryInterface;
use Kosmosx\Frontend\Services\MetatagService;
use Kosmosx\Frontend\Services\ResourcesService;

class ManagerFactory implements FactoryInterface
{

    public function metatags()
    {
        return new MetatagService();
    }

    public function resources()
    {
        return new ResourcesService();
    }
}