<?php

namespace App\Entities\OAuth\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Revocable
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $revoked = false;

    /**
     * @return bool
     */
    public function isRevoked(): bool
    {
        return $this->revoked;
    }

    /**
     * @param bool $revoked
     */
    public function setRevoked(bool $revoked)
    {
        $this->revoked = $revoked;
    }
}