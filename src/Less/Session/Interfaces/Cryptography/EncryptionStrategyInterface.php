<?php
namespace Less\Session\Interfaces\Cryptography;

/**
 * Interface EncryptionStrategy
 * @package Less\Session\Interfaces\Cryptography
 */
interface EncryptionStrategyInterface
{
    /**
     * @param $data
     * @param null $password
     * @param null $iv
     * @return string
     */
    public function encrypt($data, $password = null, $iv = null);

    /**
     * @return string
     */
    public function getIv();
}