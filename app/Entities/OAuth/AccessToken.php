<?php

namespace App\Entities\OAuth;

use App\Entities\OAuth\Traits\Expires;
use App\Entities\OAuth\Traits\Revocable;
use App\Entities\OAuth\Traits\Token;
use App\Entities\User;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;

/**
 * @ORM\Entity()
 */
class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait, Token;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @param ClientEntityInterface $client
     * @param string[] $scopes
     * @param string|null $userId
     */
    public function __construct(ClientEntityInterface $client, array $scopes = [], string $userId = null)
    {
        $this->client = $client;
        $this->scopes = $scopes;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }
}
