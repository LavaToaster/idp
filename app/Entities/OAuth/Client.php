<?php

namespace App\Entities\OAuth;

use App\Entities\OAuth\Traits\IdentifierMethods;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * @ORM\Entity()
 */
class Client implements ClientEntityInterface
{
    use IdentifierMethods;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $secret;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string[]
     *
     * @ORM\Column(type="json_array")
     */
    protected $redirectUri;

    /**
     * @var AccessToken[]
     *
     * @ORM\OneToMany(targetEntity="AccessToken", mappedBy="client")
     */
    protected $accessTokens;

//    /**
//     * @var ArrayCollection|Scope[]
//     *
//     * @ORM\ManyToMany(targetEntity="Scope")
//     * @ORM\JoinTable(name="oauth_client_scopes")
//     */
//    protected $scopes;

    /**
     * @param string $id
     * @param string $secret
     * @param string $name
     * @param string[] $redirectUri
     */
    public function __construct(
        string $id,
        string $secret,
        string $name,
        array $redirectUri = []
    )
    {
        $this->id = $id;
        $this->secret = $secret;
        $this->name = $name;
        $this->redirectUri = $redirectUri;

        $this->accessTokens = new ArrayCollection();
        $this->scopes = new ArrayCollection();
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

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return \string[]
     */
    public function getRedirectUri(): array
    {
        return $this->redirectUri;
    }

    /**
     * @param \string[] $redirectUris
     */
    public function setRedirectUri(array $redirectUris)
    {
        $this->redirectUri = $redirectUris;
    }

    /**
     * @return AccessToken[]
     */
    public function getAccessTokens()
    {
        return $this->accessTokens;
    }
}
