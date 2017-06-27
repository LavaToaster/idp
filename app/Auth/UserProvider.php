<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use LaravelDoctrine\ORM\Auth\DoctrineUserProvider;
use function Sodium\crypto_pwhash_str_verify;

class UserProvider extends DoctrineUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param Authenticatable $user
     * @param array                     $credentials
     *
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return crypto_pwhash_str_verify($user->getAuthPassword(), $credentials['password']);
    }

}