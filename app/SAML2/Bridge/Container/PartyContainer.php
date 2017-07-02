<?php

namespace App\SAML2\Bridge\Container;

use LightSaml\Build\Container\PartyContainerInterface;

class PartyContainer extends AbstractContainer implements PartyContainerInterface
{
    const IDP_ENTITY_DESCRIPTOR = 'lightsaml.container.idp_entity_descriptor';
    const SP_ENTITY_DESCRIPTOR = 'lightsaml.container.sp_entity_descriptor';
    const TRUST_OPTIONS_STORE = 'lightsaml.container.trust_options_store';

    public function getIdpEntityDescriptorStore()
    {
        return $this->container->make(self::IDP_ENTITY_DESCRIPTOR);
    }

    public function getSpEntityDescriptorStore()
    {
        return $this->container->make(self::SP_ENTITY_DESCRIPTOR);
    }

    public function getTrustOptionsStore()
    {
        return $this->container->make(self::TRUST_OPTIONS_STORE);
    }
}