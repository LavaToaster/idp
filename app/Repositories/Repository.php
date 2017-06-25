<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class Repository
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entity;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function qb(string $alias, string $indexBy = null): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select($alias)
            ->from($this->entity, $alias, $indexBy);
    }
}