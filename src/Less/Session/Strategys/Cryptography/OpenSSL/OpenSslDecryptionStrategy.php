<?php
namespace Less\Session\Strategys\Cryptography\OpenSSL;

use Less\Session\Interfaces\Cryptography\DecryptionStrategy;

/**
 * Class OpenSslDecryptionStrategy
 * @package Less\Session\Strategys\Cryptography\OpenSSL
 */
class OpenSslDecryptionStrategy extends AbstractOpenSslStrategy implements DecryptionStrategy
{
    /**
     * @param $data
     * @param null $password
     * @return string
     */
    public function decrypt($data, $password = null)
    {
        $this->optionalInitPassword($password);
        
        return openssl_decrypt(
            base64_decode($data),
            $this->getMethod(),
            $this->getKey(),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $this->getIv()
        );
    }
}