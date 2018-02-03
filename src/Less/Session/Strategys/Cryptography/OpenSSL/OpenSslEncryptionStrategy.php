<?php
namespace Less\Session\Strategys\Cryptography\OpenSSL;

use Less\Session\Interfaces\Cryptography\EncryptionStrategy;

/**
 * Class OpenSslEncryptionStrategy
 * @package Less\Session\Strategys\Cryptography\OpenSSL
 */
class OpenSslEncryptionStrategy extends AbstractOpenSslStrategy implements EncryptionStrategy
{
    /**
     * @param $data
     * @param null $password
     * @return string
     */
    public function encode($data, $password = null)
    {
        $this->optionalInitPassword($password);

        return base64_encode(
            openssl_encrypt(
                $data, $this->getMethod(),
                $this->getKey(),
                OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
                $this->getIv()
            )
        );
    }
}