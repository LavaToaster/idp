<?php

namespace App\SAML2\Bridge\Container;

use LightSaml\Build\Container\ProviderContainerInterface;
use LightSaml\Error\LightSamlBuildException;

class ProviderContainer extends AbstractContainer implements ProviderContainerInterface
{
    const ATTRIBUTE_VALUE_PROVIDER = 'lightsaml.container.attribute_value_provider';
    const SESSION_INFO_PROVIDER = 'lightsaml.container.session_info_provider';
    const NAME_ID_PROVIDER = 'lightsaml.container.name_id_provider';

    public function getAttributeValueProvider()
    {
        if (false === $this->container->bound(self::ATTRIBUTE_VALUE_PROVIDER)) {
            throw new LightSamlBuildException('Attribute value provider not set');
        }

        return $this->container->make(self::ATTRIBUTE_VALUE_PROVIDER);
    }

    public function getSessionInfoProvider()
    {
        if (false === $this->container->bound(self::SESSION_INFO_PROVIDER)) {
            throw new LightSamlBuildException('Session info provider not set');
        }

        return $this->container->make(self::SESSION_INFO_PROVIDER);
    }

    public function getNameIdProvider()
    {
        if (false === $this->container->bound(self::NAME_ID_PROVIDER)) {
            throw new LightSamlBuildException('Name ID provider not set');
        }

        return $this->container->make(self::NAME_ID_PROVIDER);
    }
}