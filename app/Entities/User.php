<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticatableTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;
use const Sodium\CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE;
use const Sodium\CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE;
use function Sodium\crypto_pwhash_str;
use function Sodium\crypto_pwhash_str_verify;

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
    protected $name;

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

    public function __construct(string $id, string $name, string $email, string $password) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->setPassword($password);
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->getEmail();
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
        $this->password = crypto_pwhash_str($password, CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE, CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE);
    }

    /**
     * @param string $password
     * @return bool
     */
    public function isValidPassword (string $password): bool
    {
        return crypto_pwhash_str_verify($this->getPassword(), $password);
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