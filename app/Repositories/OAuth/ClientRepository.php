<?php

namespace App\Repositories\OAuth;

use App\Entities\OAuth\Client;
use App\Repositories\Repository;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository extends Repository implements ClientRepositoryInterface
{
    protected $entity = Client::class;

    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier, $grantType, $clientSecret = null, $mustValidateSecret = true)
    {
        /** @var Client $client */
        $client = $this->qb('c')
            ->where('c.id = :id')
            ->setParameter('id', $clientIdentifier)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $client) {
            return null;
        }

        if ($mustValidateSecret && $clientSecret !== $client->getSecret()) {
            return null;
        }

        return $client;
    }
}
