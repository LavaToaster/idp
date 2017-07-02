<?php

namespace App\SAML2\Bridge\Container;

use LightSaml\Build\Container\OwnContainerInterface;

class OwnContainer extends AbstractContainer implements OwnContainerInterface
{
    const OWN_ENTITY_DESCRIPTOR_PROVIDER = 'lightsaml.container.own_entity_descriptor_provider';
    const OWN_CREDENTIALS = 'lightsaml.container.own_credentials';


    public function getOwnEntityDescriptorProvider()
    {
        return $this->container->make(self::OWN_ENTITY_DESCRIPTOR_PROVIDER);
    }

    public function getOwnCredentials()
    {
        return $this->container->make(self::OWN_CREDENTIALS);
    }
}