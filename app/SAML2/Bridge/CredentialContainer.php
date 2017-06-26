<?php

namespace App\SAML2\Bridge;

use LightSaml\Build\Container\CredentialContainerInterface;

class CredentialContainer extends AbstractContainer implements CredentialContainerInterface
{
    const CREDENTIAL_STORE = 'lightsaml.container.credential_store';

    public function getCredentialStore()
    {
        return $this->container->make(self::CREDENTIAL_STORE);
    }
}