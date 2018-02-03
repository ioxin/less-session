<?php
namespace Less\Session\Services;

use Less\Session\Interfaces\Cryptography\DecryptionStrategy;
use Less\Session\Interfaces\Cryptography\EncryptionStrategy;
use Less\Session\Traits\PhpSession\SessionTrait;

/**
 * Class SessionService
 * @package Less\Session\Services
 */
class SessionService
{
    use SessionTrait;

    /**
     * @hint namespace of application within the global php session
     *
     * @var string
     */
    protected $applicationSessionKey = "application.session";

    /**
     * @hint global session used from session service as pointer before writing it back to session
     *
     * @var array
     */
    protected $session = [];

    /**
     * @return array
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param array $session
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
     * @var DecryptionStrategy
     */
    protected $decryptionStrategy = null;

    /**
     * @return DecryptionStrategy
     */
    public function getDecryptionStrategy()
    {
        return $this->decryptionStrategy;
    }

    /**
     * @param DecryptionStrategy $decryptionStrategy
     */
    public function setDecryptionStrategy($decryptionStrategy)
    {
        $this->decryptionStrategy = $decryptionStrategy;
    }

    /**
     * @var EncryptionStrategy
     */
    protected $encryptionStrategy = null;

    /**
     * @return EncryptionStrategy
     */
    public function getEncryptionStrategy()
    {
        return $this->encryptionStrategy;
    }

    /**
     * @param EncryptionStrategy $encryptionStrategy
     */
    public function setEncryptionStrategy($encryptionStrategy)
    {
        $this->encryptionStrategy = $encryptionStrategy;
    }

    public function initializeSession($params = [])
    {
        $this->startSession();

        // check if global session var is persistent and the application session is included
        if ($_SESSION && isset($_SESSION[$this->getApplicationSessionKey()])) {
            //if application session key is set, the json encoded and cryptographically encoded content is read
            //into the $encryptedSessionContent var
            $encryptedSessionContent = $_SESSION[$this->getApplicationSessionKey()];

            //the $encryptedSessionContent is encoded to the json_decoded array
            $sessionContent = $this->getEncryptionStrategy()->encode($encryptedSessionContent);

            // the json_decoded array $sessionContent is now encoded into a php array
            $sessionContentArray = json_decode($sessionContent, true);

            //only the decrypted application session is used to delegate the application
            // vars outside the application session namespace are ignored because of non inclution
            $this->setSession($sessionContentArray);

        } else {
            $tempArray = array_merge($params, $this->session);
            $_SESSION[$this->getApplicationSessionKey()] = $tempArray;
        }
    }
}