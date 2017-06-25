<?php


namespace App\Repositories\OAuth;

use App\Entities\OAuth\RefreshToken;
use App\Repositories\Repository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository extends Repository implements RefreshTokenRepositoryInterface
{
    protected $entity = RefreshToken::class;

    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntityInterface)
    {
        $this->em->persist($refreshTokenEntityInterface);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        $token = $this->findById($tokenId);
        $token->setRevoked(true);

        $this->em->persist($token);
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $token = $this->findById($tokenId);

        return $token->isRevoked();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        return new RefreshToken();
    }

    /**
     * @param $tokenId
     * @return RefreshToken
     */
    private function findById($tokenId): RefreshToken
    {
        return $this->qb('t')
            ->where('t.id = :id')
            ->setParameter('id', $tokenId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
