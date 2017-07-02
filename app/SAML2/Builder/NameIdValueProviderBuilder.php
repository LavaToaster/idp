<?php

namespace App\SAML2\Builder;

use App\Entities\User;
use Illuminate\Contracts\Auth\Guard;
use LightSaml\Model\Assertion\NameID;
use LightSaml\Model\Metadata\EntityDescriptor;
use LightSaml\Provider\NameID\FixedNameIdProvider;
use LightSaml\SamlConstants;

class NameIdValueProviderBuilder
{
    /**
     * @var Guard
     */
    protected $guard;

    /**
     * @var EntityDescriptor
     */
    protected $ownEntityDescriptor;

    public function __construct(Guard $guard, EntityDescriptor $ownEntityDescriptor)
    {
        $this->guard = $guard;
        $this->ownEntityDescriptor = $ownEntityDescriptor;
    }

    /**
     * @return FixedNameIdProvider
     */
    public function build(): FixedNameIdProvider
    {
        $nameId = (new NameID())
            ->setFormat(SamlConstants::NAME_ID_FORMAT_EMAIL)
            ->setNameQualifier($this->ownEntityDescriptor->getEntityID())
            ->setValue($this->getUser()->getEmail());

        return new FixedNameIdProvider($nameId);
    }

    protected function getUser(): User
    {
        $user = $this->guard->user();

        if ($user instanceof User) {
            return $user;
        }

        return null;
    }
}