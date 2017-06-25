<?php

namespace App\Repositories\OAuth;

use App\Entities\OAuth\Scope;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    protected $entity = Scope::class;

    /**
     * {@inheritdoc}
     */
    public function getScopeEntityByIdentifier($scopeIdentifier)
    {
        return $this->findById($scopeIdentifier);

//        $scopes = [
//            'basic' => [
//                'description' => 'Basic details about you',
//            ],
//            'email' => [
//                'description' => 'Your email address',
//            ],
//        ];
//
//        if (array_key_exists($scopeIdentifier, $scopes) === false) {
//            return;
//        }
//
//        $scope = new Scope();
//        $scope->setIdentifier($scopeIdentifier);
//
//        return $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        // TODO: Some MOAR validation apparently

        return $scopes;
    }

    /**
     * @param $scopeId
     * @return Scope
     */
    private function findById($scopeId): Scope
    {
        return $this->qb('t')
            ->where('t.id = :id')
            ->setParameter('id', $scopeId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
