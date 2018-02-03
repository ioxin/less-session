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
    protected $password = '9%ff$3AklMnOpV3T!';

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
     * @return string
     */
    public function getIv()
    {
        $method = $this->getMethod();
        $ivSize = openssl_cipher_iv_length($method);
        return $iv = openssl_random_pseudo_bytes($ivSize);
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