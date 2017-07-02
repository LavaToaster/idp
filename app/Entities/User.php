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
    protected $username;

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
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    public function __construct(string $id, string $username, string $firstName, string $lastName, string $email, string $password) {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
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
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    public function getName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
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