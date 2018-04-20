<?php
namespace Less\Session\Strategys\Cryptography\Blowfish;

/**
 * Class AbstractBlowfishStrategy
 * @package Less\Session\Strategys\Cryptography\Blowfish
 */
class AbstractBlowfishStrategy
{
    /**
     * @description converts string to longinteger
     * @param $string
     * @return array
     */
    protected function convertStringToLongint($string)
    {
        $tempList = unpack('N*', $string);
        $returnLongintArray = [];
        $counter = 0;

        foreach ($tempList as $value) {
            $returnLongintArray[$counter++] = $value;
        }
        return $returnLongintArray;
    }

    /**
     * @description converts longinteger to string
     * @param $long
     * @return string
     */
    protected function convertLongintToString($long)
    {
        return pack('N', $long);
    }
}