<?php

namespace FrontManager\Factory;

use FrontManager\Factory\Interfaces\FactoryInterface;
use FrontManager\Services\MetatagService;
use FrontManager\Services\ResourcesService;

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