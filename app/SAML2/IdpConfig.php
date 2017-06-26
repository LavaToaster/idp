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

    private function getOwnCredentials(): X509Credential
    {
        /** @var FilesystemManager $fs */
        $fs = $this->buildContainer->getContainer()->make(FilesystemManager::class);

        $credential = new X509Credential(
            (new X509Certificate())->loadPem($fs->drive()->get('keys/saml.crt')),
            KeyHelper::createPrivateKey($fs->drive()->get('keys/saml.key'), null)
        );
        $credential->setEntityId(route('saml.idp.metadata'));

        return $credential;
    }
}