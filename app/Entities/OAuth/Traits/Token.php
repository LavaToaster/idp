<?php

namespace App\Entities\OAuth\Traits;

use App\Entities\OAuth\Client;
use App\Entities\OAuth\Scope;
use App\Entities\User;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Doctrine\ORM\Mapping as ORM;

trait Token
{
    use Expires, Revocable, IdentifierMethods;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\OAuth\Client", inversedBy="accessTokens")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var ScopeEntityInterface[]
     *
     * @ORM\Column(type="json_array")
     */
    private $scopes;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getUserIdentifier()
    {
        return $this->user->getId();
    }

    /**
     * @param string $identifier
     */
    public function setUserIdentifier($identifier)
    {
        // Black magic
        /** @var EntityManager $em */
        $em = app(EntityManager::class);

        $this->user = $em->getReference(User::class, $identifier);
    }

    /**
     * @return Client|ClientEntityInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client|ClientEntityInterface $client
     */
    public function setClient(ClientEntityInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return ScopeEntityInterface[]
     */
    public function getScopes()
    {
        return array_map(function($scopeId) {

            $scope = new Scope();
            $scope->setKey($scopeId);

            return $scope;
        }, $this->scopes);
    }

    /**
     * Associate a scope with the token.
     *
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope)
    {
        $this->scopes[] = $scope->getIdentifier();
    }
}