<?php
namespace Less\Session\Interfaces\Cryptography;

/**
 * Interface DecryptionStrategy
 * @package Less\Session\Interfaces\Cryptography
 */
interface DecryptionStrategyInterface
{


    /**
     * @param $data
     * @param null $password
     * @param null $iv
     * @return mixed
     */
    public function decrypt($data, $password = null, $iv = null);
}