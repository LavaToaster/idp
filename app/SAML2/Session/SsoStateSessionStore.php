<?php

namespace App\SAML2\Session;

use Illuminate\Contracts\Session\Session;
use LightSaml\State\Sso\SsoState;
use LightSaml\Store\Sso\SsoStateStoreInterface;

class SsoStateSessionStore implements SsoStateStoreInterface
{
    /** @var Session */
    protected $session;

    /** @var string */
    protected $key;

    /**
     * @param Session $session
     * @param string           $key
     */
    public function __construct(Session $session, $key)
    {
        $this->session = $session;
        $this->key = $key;
    }

    /**
     * @return SsoState
     */
    public function get()
    {
        $result = $this->session->get($this->key);

        if (null === $result) {
            $result = new SsoState();
            $this->set($result);
        }

        return $result;
    }

    /**
     * @param SsoState $ssoState
     *
     * @return void
     */
    public function set(SsoState $ssoState)
    {
        $ssoState->setLocalSessionId($this->session->getId());
        $this->session->put($this->key, $ssoState);
    }
}