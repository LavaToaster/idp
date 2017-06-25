<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticatableTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * @ORM\Entity()
 */
class User implements Authenticatable, UserEntityInterface
{
    use AuthenticatableTrait, Timestamps;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    public function __construct(string $id, string $email, string $password) {
        $this->id = $id;
        $this->email = $email;
        $this->password = bcrypt($password);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = bcrypt($password);
    }

    /**
     * @param string $password
     * @return bool
     */
    public function isValidPassword (string $password): bool
    {
        return password_verify($password, $this->getPassword());
    }

    /**
     * Return the user's identifier.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->getId();
    }
}