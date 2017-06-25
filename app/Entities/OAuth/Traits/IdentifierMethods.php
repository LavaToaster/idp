<?php

namespace App\Entities\OAuth\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IdentifierMethods
{
    public function getIdentifier()
    {
        return $this->getId();
    }

    public function setIdentifier($identifier)
    {
        return $this->setId($identifier);
    }
}