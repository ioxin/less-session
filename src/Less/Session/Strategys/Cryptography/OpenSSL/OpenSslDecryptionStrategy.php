<?php
namespace Less\Session\Strategys\Cryptography\OpenSSL;

use Less\Session\Interfaces\Cryptography\DecryptionStrategyInterface;

/**
 * Class OpenSslDecryptionStrategy
 * @package Less\Session\Strategys\Cryptography\OpenSSL
 */
class OpenSslDecryptionStrategy extends AbstractOpenSslStrategy implements DecryptionStrategyInterface
{
    /**
     * @param $data
     * @param null $password
     * @param null $iv
     * @return mixed
     */
    public function decrypt($data, $password = null, $iv = null)
    {
//        $iv = $iv ? $iv : $this->getIv();
        
        return openssl_decrypt(
            $data,
            $this->getMethod(),
            $this->getPassword(),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $iv
        );
    }
}