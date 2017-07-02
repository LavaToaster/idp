<?php

namespace App\SAML2\Store;

use Illuminate\Filesystem\FilesystemAdapter;
use LightSaml\Model\Metadata\EntitiesDescriptor;
use LightSaml\Model\Metadata\EntityDescriptor;
use LightSaml\Store\EntityDescriptor\FixedEntityDescriptorStore;

class EntityDescriptorStoreBuilder
{
    /**
     * @var FilesystemAdapter
     */
    protected $drive;

    /**
     * @var FixedEntityDescriptorStore
     */
    protected $provider;

    public function __construct(FilesystemAdapter $drive)
    {
        $this->drive = $drive;
    }


    public function build()
    {
        $idpProvider = new FixedEntityDescriptorStore();

        $providers = array_merge(
            $this->getFromConfig(),
            $this->getFromDisk()
        );

        foreach ($providers as $provider) {
            $idpProvider->add(
                $this->getEntityDescriptor($provider)
            );
        }

        return $this->provider = $idpProvider;
    }

    /**
     * @param string $xml
     * @return EntityDescriptor|EntitiesDescriptor
     */
    protected function getEntityDescriptor(string $xml)
    {
        if (str_contains($xml, 'EntitiesDescriptor')) {
            return EntitiesDescriptor::loadXml($xml);
        }

        return EntityDescriptor::loadXml($xml);
    }

    /**
     * @return string[]
     */
    protected function getFromConfig(): array
    {
        return config('saml.providers', []);
    }

    /**
     * @return string []
     */
    protected function getFromDisk(): array
    {
        $providers = [];

        foreach ($this->drive->files('providers') as $filePath) {
            $fileInfo = pathinfo($filePath);

            if ($fileInfo['extension'] !== 'xml') {
                continue;
            }

            $providers[] = $this->drive->get($filePath);
        }

        return $providers;
    }
}