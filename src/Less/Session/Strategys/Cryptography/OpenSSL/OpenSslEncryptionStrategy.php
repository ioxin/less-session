<?php
namespace Less\Session\Strategys\Cryptography\OpenSSL;

use Less\Session\Interfaces\Cryptography\EncryptionStrategyInterface;

/**
 * Class OpenSslEncryptionStrategy
 * @package Less\Session\Strategys\Cryptography\OpenSSL
 */
class OpenSslEncryptionStrategy extends AbstractOpenSslStrategy implements EncryptionStrategyInterface
{
    /**
     * @param $data
     * @param null $password
     * @param null $iv
     * @return string
     */
    public function encrypt($data, $password = null, $iv = null)
    {
        //        $iv = $iv ? $iv : $this->getIv();
        return openssl_encrypt(
            $data,
            $this->getMethod(),
            $this->getPassword(),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $iv
        );
    }
}