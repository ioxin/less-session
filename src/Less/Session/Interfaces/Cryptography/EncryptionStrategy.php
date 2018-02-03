<?php
namespace Less\Session\Interfaces\Cryptography;

/**
 * Interface EncryptionStrategy
 * @package Less\Session\Interfaces\Cryptography
 */
interface EncryptionStrategy
{
    /**
     * @param $data
     * @param null $password
     * @return string
     */
    public function encode($data, $password = null);
}