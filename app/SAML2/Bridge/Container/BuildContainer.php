<?php

namespace App\SAML2\Bridge\Container;

use LightSaml\Build\Container\BuildContainerInterface;
use LightSaml\Build\Container\CredentialContainerInterface;
use LightSaml\Build\Container\OwnContainerInterface;
use LightSaml\Build\Container\PartyContainerInterface;
use LightSaml\Build\Container\ProviderContainerInterface;
use LightSaml\Build\Container\ServiceContainerInterface;
use LightSaml\Build\Container\StoreContainerInterface;
use LightSaml\Build\Container\SystemContainerInterface;

class BuildContainer extends AbstractContainer implements BuildContainerInterface
{
    /** 
     * @var SystemContainerInterface 
     */
    private $systemContainer;

    /** 
     * @var PartyContainerInterface 
     */
    private $partyContainer;

    /** 
     * @var StoreContainerInterface 
     */
    private $storeContainer;

    /** 
     * @var ProviderContainerInterface 
     */
    private $providerContainer;

    /** 
     * @var CredentialContainerInterface 
     */
    private $credentialContainer;

    /** 
     * @var ServiceContainerInterface 
     */
    private $serviceContainer;

    /** 
     * @var OwnContainerInterface 
     */
    private $ownContainer;

    public function getSystemContainer()
    {
        if (null === $this->systemContainer) {
            $this->systemContainer = new SystemContainer($this->container);
        }

        return $this->systemContainer;
    }

    public function getPartyContainer()
    {
        if (null === $this->partyContainer) {
            $this->partyContainer = new PartyContainer($this->container);
        }

        return $this->partyContainer;
    }

    public function getStoreContainer()
    {
        if (null === $this->storeContainer) {
            $this->storeContainer = new StoreContainer($this->container);
        }

        return $this->storeContainer;
    }

    public function getProviderContainer()
    {
        if (null === $this->providerContainer) {
            $this->providerContainer = new ProviderContainer($this->container);
        }

        return $this->providerContainer;
    }

    public function getCredentialContainer()
    {
        if (null === $this->credentialContainer) {
            $this->credentialContainer = new CredentialContainer($this->container);
        }

        return $this->credentialContainer;
    }

    public function getServiceContainer()
    {
        if (null === $this->serviceContainer) {
            $this->serviceContainer = new ServiceContainer($this->container);
        }

        return $this->serviceContainer;
    }

    public function getOwnContainer()
    {
        if (null === $this->ownContainer) {
            $this->ownContainer = new OwnContainer($this->container);
        }

        return $this->ownContainer;
    }
}