<?php

namespace App\SAML2\Bridge;

use LightSaml\Bridge\Pimple\Container\AbstractPimpleContainer;
use LightSaml\Build\Container\StoreContainerInterface;

class StoreContainer extends AbstractContainer implements StoreContainerInterface
{
    const REQUEST_STATE_STORE = 'lightsaml.container.request_state_store';
    const ID_STATE_STORE = 'lightsaml.container.id_state_store';
    const SSO_STATE_STORE = 'lightsaml.container.sso_state_store';

    public function getRequestStateStore()
    {
        return $this->container->make(self::REQUEST_STATE_STORE);
    }

    public function getIdStateStore()
    {
        return $this->container->make(self::ID_STATE_STORE);
    }

    public function getSsoStateStore()
    {
        return $this->container->make(self::SSO_STATE_STORE);
    }
}
