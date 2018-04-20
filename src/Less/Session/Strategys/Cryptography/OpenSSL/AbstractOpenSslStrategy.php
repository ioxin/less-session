<?php
namespace Less\Session\Strategys\Cryptography\OpenSSL;

/**
 * Class AbstractOpenSslStrategy
 * @package Less\Session\Strategys\Cryptography\OpenSSL
 */
class AbstractOpenSslStrategy
{
    /**
     * @var string
     */
    protected $password = '9ff3AklMnOpV3T';

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @var string
     */
    protected $method = 'AES-128-CFB8';


    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return int
     */
    public function getStaticIv()
    {
        return 9285392853147264;
    }

    /**
     * @param int $length
     * @return int
     */
    private function getRandomBytesByLength($length = 16)
    {
        return random_bytes($length);

    }


    /**
     * @return string
     */
    public function getIv()
    {
        return $this->getRandomBytesByLength(openssl_cipher_iv_length($this->getMethod()));
    }

    /**
     * @return bool|string
     */
    protected function getKey()
    {
        return $key = password_hash($this->getPassword(), PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * @param $password
     * @return bool
     */
    public function optionalInitPassword($password)
    {
        if (!is_null($password)) {
            $this->setPassword($password);
            return true;
        }
        return false;
    }
}