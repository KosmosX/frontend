<?php

namespace ResourcesManager\Factory;

use ResourcesManager\Factory\Interfaces\FactoryInterface;
use ResourcesManager\Services\MetatagService;
use ResourcesManager\Services\ResourcesService;

class FrontManagerFactory implements FactoryInterface
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