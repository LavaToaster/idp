<?php

namespace App\SAML2\Bridge\Container;

use LightSaml\Build\Container\SystemContainerInterface;

class SystemContainer extends AbstractContainer implements SystemContainerInterface
{
    const REQUEST = 'lightsaml.container.request';
    const SESSION = 'lightsaml.container.session';
    const TIME_PROVIDER = 'lightsaml.container.time_provider';
    const EVENT_DISPATCHER = 'lightsaml.container.event_dispatcher';
    const LOGGER = 'lightsaml.container.logger';

    public function getRequest()
    {
        return $this->container->make(self::REQUEST);
    }

    public function getSession()
    {
        return $this->container->make(self::SESSION);
    }

    public function getTimeProvider()
    {
        return $this->container->make(self::TIME_PROVIDER);
    }

    public function getEventDispatcher()
    {
        return $this->container->make(self::EVENT_DISPATCHER);
    }

    public function getLogger()
    {
        return $this->container->make(self::LOGGER);
    }
}
