<?php
namespace Less\Session\Strategys\Cryptography\Aes;

use Less\Session\Interfaces\Cryptography\EncryptionStrategyInterface;

/**
 * Class Aes256EncryptionStrategy
 * @package Less\Session\Strategys\Cryptography\Aes
 */
class Aes256EncryptionStrategy implements EncryptionStrategyInterface
{
    /**
     * @param $data
     * @param $password
     * @param $iv
     * @return string
     */
    public function encrypt($data, $password = null, $iv = null)
    {
        return $this->encryptAES($data, $password, $iv);
    }

    /**
     * @return string
     */
    public function getIv()
    {
        $cp = $this->getCp();

        // Ermittelt den Initialisierungsvector, der für die Modi CBC, CFB
        // und OFB benötigt wird.
        // Der Initialisierungsvector muss beim Entschlüsseln den selben
        // Wert wie beim Verschlüsseln haben.
        // Windows unterstützt nur MCRYPT_RAND
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($cp), MCRYPT_RAND);
        } else {
            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($cp), MCRYPT_DEV_RANDOM);
        }

        return $iv;
    }

    /**
     * @return resource
     */
    protected function getCp()
    {
        // Setzt den Verschlüsselungsalgorithmus
        // und setzt den Output Feedback (OFB) Modus
        return mcrypt_module_open('rijndael-256', '', 'ofb', '');
    }

    /**
     * @param $content
     * @param $key
     * @param $iv
     * @return string
     */
    public function encryptAES($content, $key, $iv)
    {
        $cp = $this->getCp();

        // Ermittelt die Anzahl der Bits, welche die Schlüssellänge
        // des Keys festlegen
        $ks = mcrypt_enc_get_key_size($cp);

        // Erstellt den Schlüssel, der für die Verschlüsselung genutzt wird
        $key = substr(md5($key), 0, $ks);

        // Initialisiert die Verschlüsselung
        mcrypt_generic_init($cp, $key, $iv);

        // Verschlüsselt die Daten
        $encrypted = mcrypt_generic($cp, $content);

        // Deinitialisiert die Verschlüsselung
        mcrypt_generic_deinit($cp);

        // Schließt das Modul
        mcrypt_module_close($cp);

        return $encrypted;
    }
}