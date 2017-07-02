<?php

namespace App\SAML2\Provider;

use App\Entities\User;
use Illuminate\Contracts\Auth\Guard;
use LightSaml\ClaimTypes;
use LightSaml\Model\Assertion\Attribute;
use LightSaml\Provider\Attribute\FixedAttributeValueProvider;

class AttributeValueProviderBuilder
{
    /**
     * @var Guard
     */
    protected $guard;

    /**
     * @var FixedAttributeValueProvider
     */
    protected $provider;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * @return FixedAttributeValueProvider
     */
    public function build(): FixedAttributeValueProvider
    {
        if ($this->provider) {
            return $this->provider;
        }

        $provider = new FixedAttributeValueProvider();
        $provider->setAttributes($this->getMapping());

        return $this->provider = $provider;
    }
    
    protected function getUser(): User
    {
        $user = $this->guard->user();
        
        if ($user instanceof User) {
            return $user;
        }
    }

    /**
     * @return Attribute[]
     */
    protected function getMapping(): array
    {
        $user = $this->getUser();
        
        return [
            new Attribute(
                ClaimTypes::PPID,
                $user->getId()
            ),
            new Attribute(
                ClaimTypes::NAME,
                $user->getFirstName()
            ),
            new Attribute(
                ClaimTypes::GIVEN_NAME,
                $user->getFirstName()
            ),
            new Attribute(
                ClaimTypes::SURNAME,
                $user->getLastName()
            ),
            new Attribute(
                ClaimTypes::EMAIL_ADDRESS,
                $user->getEmail()
            )
        ];
    }
}