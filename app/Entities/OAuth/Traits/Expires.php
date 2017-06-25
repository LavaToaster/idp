<?php

namespace App\Entities\OAuth\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Expires
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @return \DateTime
     */
    public function getExpiryDateTime()
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $expiry
     */
    public function setExpiryDateTime(\DateTime $expiry)
    {
        $this->expiresAt = $expiry;
    }
}