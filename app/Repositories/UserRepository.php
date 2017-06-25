<?php

namespace App\Repositories;

use App\Entities\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository extends Repository implements UserRepositoryInterface
{
    protected $entity = User::class;

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        /** @var User $user */
        $user = $this->qb('u')
            ->where('u.email = :email')
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            return null;
        }

        if (false === $user->isValidPassword($password)) {
            return null;
        }

        return $user;
    }
}
