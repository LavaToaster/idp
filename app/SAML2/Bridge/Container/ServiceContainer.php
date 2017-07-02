<?php

namespace App\SAML2\Bridge\Container;

use LightSaml\Build\Container\ServiceContainerInterface;

class ServiceContainer extends AbstractContainer implements ServiceContainerInterface
{
    const ASSERTION_VALIDATOR = 'lightsaml.container.assertion_validator';
    const ASSERTION_TIME_VALIDATOR = 'lightsaml.container.assertion_time_validator';
    const SIGNATURE_RESOLVER = 'lightsaml.container.signature_resolver';
    const ENDPOINT_RESOLVER = 'lightsaml.container.endpoint_resolver';
    const NAME_ID_VALIDATOR = 'lightsaml.container.name_id_validator';
    const BINDING_FACTORY = 'lightsaml.container.binding_factory';
    const SIGNATURE_VALIDATOR = 'lightsaml.container.signature_validator';
    const CREDENTIAL_RESOLVER = 'lightsaml.container.credential_resolver';
    const LOGOUT_SESSION_RESOLVER = 'lightsaml.container.logout_session_resolver';
    const SESSION_PROCESSOR = 'lightsaml.container.session_processor';

    public function getAssertionValidator()
    {
        return $this->container->make(self::ASSERTION_VALIDATOR);
    }

    public function getAssertionTimeValidator()
    {
        return $this->container->make(self::ASSERTION_TIME_VALIDATOR);
    }

    public function getSignatureResolver()
    {
        return $this->container->make(self::SIGNATURE_RESOLVER);
    }

    public function getEndpointResolver()
    {
        return $this->container->make(self::ENDPOINT_RESOLVER);
    }

    public function getNameIdValidator()
    {
        return $this->container->make(self::NAME_ID_VALIDATOR);
    }

    public function getBindingFactory()
    {
        return $this->container->make(self::BINDING_FACTORY);
    }

    public function getSignatureValidator()
    {
        return $this->container->make(self::SIGNATURE_VALIDATOR);
    }

    public function getCredentialResolver()
    {
        return $this->container->make(self::CREDENTIAL_RESOLVER);
    }

    public function getLogoutSessionResolver()
    {
        return $this->container->make(self::LOGOUT_SESSION_RESOLVER);
    }

    public function getSessionProcessor()
    {
        return $this->container->make(self::SESSION_PROCESSOR);
    }
}