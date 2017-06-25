<?php

namespace App\Http\Middleware;

use Closure;
use Doctrine\ORM\EntityManager;

class Flush
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $this->em->flush();

        return $response;
    }
}