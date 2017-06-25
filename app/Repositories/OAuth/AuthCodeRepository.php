<?php

namespace App\Repositories\OAuth;

use App\Entities\OAuth\AuthCode;
use App\Repositories\Repository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository extends Repository implements AuthCodeRepositoryInterface
{
    protected $entity = AuthCode::class;

    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $AuthCodeEntityInterface)
    {
        $this->em->persist($AuthCodeEntityInterface);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAuthCode($tokenId)
    {
        $token = $this->findById($tokenId);
        $token->setRevoked(true);

        $this->em->persist($token);
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($tokenId)
    {
        $token = $this->findById($tokenId);

        return $token->isRevoked();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode()
    {
        return new AuthCode();
    }

    /**
     * @param $tokenId
     * @return AuthCode
     */
    private function findById($tokenId): AuthCode
    {
        return $this->qb('t')
            ->where('t.id = :id')
            ->setParameter('id', $tokenId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
