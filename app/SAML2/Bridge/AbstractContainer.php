<?php

namespace App\SAML2\Bridge;

use Illuminate\Contracts\Container\Container as LaravelContainer;

abstract class AbstractContainer
{
    /**
     * @var LaravelContainer
     */
    protected $container;

    public function __construct(LaravelContainer $container)
    {

        $this->container = $container;
    }

    /**
     * @return LaravelContainer
     */
    public function getContainer(): LaravelContainer
    {
        return $this->container;
    }
}