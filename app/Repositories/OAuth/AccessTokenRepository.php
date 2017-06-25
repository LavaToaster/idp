<?php

namespace App\Repositories\OAuth;

use App\Entities\OAuth\AccessToken;
use App\Repositories\Repository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository extends Repository implements AccessTokenRepositoryInterface
{
    protected $entity = AccessToken::class;

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $this->em->persist($accessTokenEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
        $token = $this->findById($tokenId);
        $token->setRevoked(true);

        $this->em->persist($token);
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $token = $this->findById($tokenId);

        return $token->isRevoked();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessToken($clientEntity);

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }

    /**
     * @param $tokenId
     * @return AccessToken
     */
    private function findById($tokenId): AccessToken
    {
        return $this->qb('t')
            ->where('t.id = :id')
            ->setParameter('id', $tokenId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
