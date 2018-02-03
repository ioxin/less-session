<?php
namespace Less\Session\Interfaces\Cryptography;

/**
 * Interface DecryptionStrategy
 * @package Less\Session\Interfaces\Cryptography
 */
interface DecryptionStrategy
{
    /**
     * @param $data
     * @param null $password
     * @return string
     */
    public function decrypt($data, $password = null);
}