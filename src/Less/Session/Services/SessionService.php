<?php
namespace Less\Session\Services;

use Less\Session\Containers\Session;
use Less\Session\Interfaces\Cryptography\DecryptionStrategyInterface;
use Less\Session\Interfaces\Cryptography\EncryptionStrategyInterface;

/**
 * Class SessionService
 * @package Less\Session\Services
 */
class SessionService
{
    /**
     * @hint namespace of application within the global php session
     *
     * @var string
     */
    protected $applicationSessionKey = "application.session";

    /**
     * @hint global session used from session service as pointer before writing it back to session
     *
     * @var Session
     */
    protected $session;

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getApplicationSessionKey()
    {
        return $this->applicationSessionKey;
    }

    /**
     * @var DecryptionStrategyInterface
     */
    protected $decryptionStrategy = null;

    /**
     * @return DecryptionStrategyInterface
     */
    public function getDecryptionStrategy()
    {
        return $this->decryptionStrategy;
    }

    /**
     * @param DecryptionStrategyInterface $decryptionStrategy
     */
    public function setDecryptionStrategy($decryptionStrategy)
    {
        $this->decryptionStrategy = $decryptionStrategy;
    }

    /**
     * @var EncryptionStrategyInterface
     */
    protected $encryptionStrategy = null;

    /**
     * @return EncryptionStrategyInterface
     */
    public function getEncryptionStrategy()
    {
        return $this->encryptionStrategy;
    }

    /**
     * @param EncryptionStrategyInterface $encryptionStrategy
     */
    public function setEncryptionStrategy($encryptionStrategy)
    {
        $this->encryptionStrategy = $encryptionStrategy;
    }

    /**
     * SessionService constructor.
     * @param Session $session
     * @param EncryptionStrategyInterface $encryptionStrategy
     * @param DecryptionStrategyInterface $decryptionStrategy
     */
    public function __construct(Session $session, EncryptionStrategyInterface $encryptionStrategy, DecryptionStrategyInterface $decryptionStrategy)
    {
        $this->setSession($session);
        $this->setEncryptionStrategy($encryptionStrategy);
        $this->setDecryptionStrategy($decryptionStrategy);

        $this->initSession();
    }

    /**
     * @hint Proxy Method for easy usage
     *
     * @return bool
     */
    public function initSession()
    {
        return $this->initApplicationSession();
    }

    /**
     * @hint Proxy Method for developers semantic understanding, which session will be started
     *
     * @return bool
     */
    public function initApplicationSession()
    {
        return $this->initializeSessionNamespace($this->getApplicationSessionKey(), $this->getEncryptionStrategy(), $this->getDecryptionStrategy());
    }

    /**
     * @hint General Session starter for a namespace
     *
     * @param string $namespace
     * @param EncryptionStrategyInterface $encryptionStrategy
     * @param DecryptionStrategyInterface $decryptionStrategy
     * @return bool
     */
    public function initializeSessionNamespace($namespace, EncryptionStrategyInterface $encryptionStrategy, DecryptionStrategyInterface $decryptionStrategy)
    {
        if (is_null($namespace)) {
            return false;
        }
        $session = $this->getSession();
        if ($session instanceof Session) {
            return $session->initializeSession($namespace, $encryptionStrategy, $decryptionStrategy);
        }
        return false;
    }

    /**
     * @hint Proxy Method for easy usage
     *
     * @return bool
     */
    public function refreshSession()
    {
        return $this->refreshApplicationSession();
    }

    /**
     * @hint Proxy Method for developers semantic understanding, which session will be refreshed
     *
     * @return bool
     */
    public function refreshApplicationSession()
    {
        return $this->refreshSessionNamespace($this->getApplicationSessionKey(), $this->getEncryptionStrategy(), $this->getDecryptionStrategy());
    }

    /**
     * @hint General Session Refresher for a namespace
     *
     * @param string $namespace
     * @param EncryptionStrategyInterface $encryptionStrategy
     * @param DecryptionStrategyInterface $decryptionStrategy
     * @return bool
     * @throws \Exception
     */
    public function refreshSessionNamespace($namespace, EncryptionStrategyInterface $encryptionStrategy, DecryptionStrategyInterface $decryptionStrategy)
    {
        if (is_null($namespace)) {
            return false;
        }
        $session = $this->getSession();
        if ($session instanceof Session) {
            return $session->refreshSession($namespace, $encryptionStrategy, $decryptionStrategy);
        }
        return false;
    }

    /**
     * @param $string
     * @return bool
     */
    public function removeNamespace($string)
    {
        if (is_null($string) || $string == '' || !$string) {
            return false;
        }

        $this->getSession()->removeNamespace($string);

        return true;
    }
}