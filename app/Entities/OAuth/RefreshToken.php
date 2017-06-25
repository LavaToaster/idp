<?php

namespace App\Entities\OAuth;

use App\Entities\OAuth\Traits\Expires;
use App\Entities\OAuth\Traits\IdentifierMethods;
use App\Entities\OAuth\Traits\Revocable;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

/**
 * @ORM\Entity()
 */
class RefreshToken implements RefreshTokenEntityInterface
{
    use IdentifierMethods, Expires, Revocable;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var AccessToken
     *
     * @ORM\ManyToOne(targetEntity="AccessToken")
     * @ORM\JoinColumn(name="access_token_id", referencedColumnName="id")
     */
    protected $accessToken;

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

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken(AccessTokenEntityInterface $accessToken)
    {
        $this->accessToken = $accessToken;
    }
}
