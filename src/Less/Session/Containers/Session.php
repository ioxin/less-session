<?php
namespace Less\Session\Containers;

use Less\Session\Interfaces\Cryptography\DecryptionStrategyInterface;
use Less\Session\Interfaces\Cryptography\EncryptionStrategyInterface;
use Less\Session\Traits\PhpSession\SessionTrait;

/**
 * Class Session
 * @package Less\Session\Containers
 */
class Session
{
    use SessionTrait;

    /**
     * @var bool
     */
    protected $sessionStarted = false;

    /**
     * @return boolean
     */
    public function isSessionStarted()
    {
        return $this->sessionStarted;
    }

    /**
     * @param boolean $sessionStarted
     */
    public function setSessionStarted($sessionStarted)
    {
        $this->sessionStarted = $sessionStarted;
    }

    /**
     * @hint equivalent pointer to $_SESSION
     */
    protected $session;

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @var \stdClass
     */
    protected $decodedSession;

    /**
     * @return \stdClass
     */
    public function getDecodedSession()
    {
        return $this->decodedSession;
    }

    /**
     * @param \stdClass $decodedSession
     */
    public function setDecodedSession($decodedSession)
    {
        $this->decodedSession = $decodedSession;
    }

    /**
     * Session constructor.
     */
    public function __construct()
    {
        $this->startSessionIfNotStarted();

        $this->setSession($_SESSION);
    }

    /**
     * @return bool
     */
    public function startSessionIfNotStarted()
    {
        if ($this->isSessionStarted() == false) {
            $this->startSession();
            $this->setSessionStarted(true);

            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $sessionNamespace
     * @param EncryptionStrategyInterface $encryptionStrategy
     * @return bool
     */
    protected function initializeSessionNamespace($sessionNamespace, EncryptionStrategyInterface $encryptionStrategy)
    {
        $iv = $encryptionStrategy->getIv();

        $expireDate = (new \DateTime())->modify('+1 day');

        $json = [
            'expire_date' => $expireDate
        ];
        $encodedContent = $encryptionStrategy->encrypt(json_encode($json), null, $iv);

        $_SESSION[$sessionNamespace] = [
            'encryption' => [
                'iv' => $iv,
            ],
            'expire_date' => $expireDate,
            'content' => $encodedContent
        ];

        return true;
    }

    /**
     * @param $sessionNamespace
     * @param EncryptionStrategyInterface $encryptionStrategy
     * @return mixed
     */
    public function readApplicationSessionOrInitializeItFromNamespace($sessionNamespace, EncryptionStrategyInterface $encryptionStrategy)
    {
        if (!isset($_SESSION[$sessionNamespace])) {
            $this->initializeSessionNamespace($sessionNamespace, $encryptionStrategy);
        }

        return $_SESSION[$sessionNamespace];
    }


    /**
     * @param $sessionNamespace
     * @param EncryptionStrategyInterface $encryptionStrategy
     * @param DecryptionStrategyInterface $decryptionStrategy
     * @return bool
     * @throws \Exception
     */
    public function refreshSession($sessionNamespace, EncryptionStrategyInterface $encryptionStrategy, DecryptionStrategyInterface $decryptionStrategy)
    {
        if (!$this->isSessionStarted()) {
            throw new \Exception('session not started yet. (Session is started by constructor)');
        }

        // read encrypted session from default application namespace or from new namespace
        $encodedSession = $this->readApplicationSessionOrInitializeItFromNamespace($sessionNamespace, $encryptionStrategy);

        // decode session content from new or already existent session
        $decodedContent = $this->decodeSessionContent($encodedSession, $decryptionStrategy);

        // get new expire date
        $expireDate = (new \DateTime())->modify('+1 day');
        // get new iv to change encryption
        $iv = $encryptionStrategy->getIv();

        // after updating expire date in decoded content, encode it again
        $decodedContent['expire_date'] = $expireDate;
        $encodedContent = $encryptionStrategy->encrypt(json_encode($decodedContent), null, $iv);

        // set new iv, new visible expire date and new encoded/encrypted content and write to session
        $encodedSession['encryption']['iv'] = $iv;
        $encodedSession['expire_date'] = $expireDate;
        $encodedSession['content'] = $encodedContent;
        $_SESSION[$sessionNamespace] = $encodedSession;

        $this->setDecodedSession($decodedContent);
        $this->setSession($_SESSION);

        return true;
    }

    /**
     * @param $sessionNamespace
     * @param EncryptionStrategyInterface $encryptionStrategy
     * @param DecryptionStrategyInterface $decryptionStrategy
     * @return bool
     */
    public function initializeSession($sessionNamespace, EncryptionStrategyInterface $encryptionStrategy, DecryptionStrategyInterface $decryptionStrategy)
    {
        if (!isset($_SESSION)) {
            $this->startSessionIfNotStarted();
        }

        // read encrypted session from default application namespace or from new namespace
        $encodedSession = $this->readApplicationSessionOrInitializeItFromNamespace($sessionNamespace, $encryptionStrategy);

        // decode session content from new or already existent session
        $decodedContent = $this->decodeSessionContent($encodedSession, $decryptionStrategy);

        // make session available in class for global usage of session object
        $this->setDecodedSession($decodedContent);

        return true;
    }

    /**
     * @param array $encodedSession
     * @param DecryptionStrategyInterface $decryptionStrategy
     * @return array|mixed
     */
    protected function decodeSessionContent(array $encodedSession, DecryptionStrategyInterface $decryptionStrategy)
    {
        $decodedContent = [];

        $encodedContent = null;
        if (isset($encodedSession['content'])) {
            $encodedContent = $encodedSession['content'];
        }
        $iv = null;
        if (isset($encodedSession['encryption']['iv'])) {
            $iv = $encodedSession['encryption']['iv'];
        }

        if (is_string($encodedContent) && is_string($iv)) {
            $plainContent = $decryptionStrategy->decrypt($encodedContent, null, $iv);
            $decodedContent = json_decode($plainContent, true);
        }

        return $decodedContent;
    }

    /**
     * @param $sessionNamespace
     * @param EncryptionStrategyInterface $encryptionStrategy
     * @param $data
     */
    public function addToNamespace($sessionNamespace, EncryptionStrategyInterface $encryptionStrategy, $data)
    {
        $decodedSession = $this->getDecodedSession();
        if (isset($decodedSession[$sessionNamespace]) && is_array($decodedSession[$sessionNamespace])) {
            $temp = $decodedSession[$sessionNamespace];
            $decodedSession[$sessionNamespace] = array_merge($temp, $data);
        } else {
            $decodedSession[$sessionNamespace] = $data;
        }

        $this->setDecodedSession($decodedSession);

        $plainSessionContent = $decodedSession;

        $json = json_encode($plainSessionContent);
        $encoded = $encryptionStrategy->encrypt($json);
        $_SESSION[$sessionNamespace] = $encoded;
    }

    /**
     * @param $sessionNamespace
     * @param EncryptionStrategyInterface $encryptionStrategy
     * @param $data
     */
    public function writeToNamespace($sessionNamespace, EncryptionStrategyInterface $encryptionStrategy, $data)
    {

    }

    /**
     * @param $string
     * @return bool
     */
    public function removeNamespace($string)
    {
        unset($_SESSION[$string]);
        unset($this->getSession()[$string]);
        unset($this->getDecodedSession()[$string]);
        return true;
    }
}