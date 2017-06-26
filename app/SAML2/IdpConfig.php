<?php

namespace App\SAML2;

use App\SAML2\Bridge\BuildContainer;
use Illuminate\Filesystem\FilesystemManager;
use LightSaml\Credential\KeyHelper;
use LightSaml\Credential\X509Certificate;
use LightSaml\Credential\X509Credential;

class IdpConfig
{
    /**
     * @var BuildContainer
     */
    private $buildContainer;

    public function __construct(BuildContainer $buildContainer)
    {
        $this->buildContainer = $buildContainer;
    }

}