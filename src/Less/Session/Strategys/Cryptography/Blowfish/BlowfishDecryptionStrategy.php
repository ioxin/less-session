<?php
namespace Less\Session\Strategys\Cryptography\Blowfish;

/**
 * Class BlowfishDecryptionStrategy
 * @package Less\Session\Strategys\Cryptography\Blowfish
 */
class BlowfishDecryptionStrategy extends AbstractBlowfishStrategy
{

    //Entschluesseln
    function decrypt($text)
    {
        $plain = [];
        $cipher = $this->convertStringToLongint(base64_decode($text));

        if (CBC == 1)
            $index = 2; //Message start at second block
        else
            $index = 0; //Message start at first block

        $index = 0;
        if (CBC == 1) {
            $index = 2;
        }

        for ($index; $index < count($cipher); $index += 2) {
            $return = $this->block_decrypt($cipher[$index], $cipher[$index + 1]);

            //Xor Verknuepfung von $return und Geheimtext aus von den letzten beiden Bloecken
            //XORed $return with the previous ciphertext
            if (CBC == 1)
                $plain[] = array($return[0] ^ $cipher[$index - 2], $return[1] ^ $cipher[$index - 1]);
            else          //EBC Mode
                $plain[] = $return;
        }

        $output = "";
        for ($index = 0; $index < count($plain); $index++) {
            $output .= $this->convertLongintToString($plain[$index][0]);
            $output .= $this->convertLongintToString($plain[$index][1]);
        }

        return $output;
    }

}