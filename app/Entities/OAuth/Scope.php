<?php

namespace App\Entities\OAuth;

use App\Entities\OAuth\Traits\IdentifierMethods;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * @ORM\Entity()
 */
class Scope implements ScopeEntityInterface
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }

    public function getIdentifier(): string
    {
        return $this->getKey();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function jsonSerialize()
    {
        return $this->getKey();
    }
}
