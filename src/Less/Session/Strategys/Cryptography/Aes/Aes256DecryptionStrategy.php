<?php
namespace Less\Session\Strategys\Cryptography\Aes;

/**
 * Class Aes256DecryptionStrategy
 * @package Less\Session\Strategys\Cryptography\Aes
 */
class Aes256DecryptionStrategy
{
    /**
     * @param $data
     * @param $password
     * @param $iv
     * @return string
     */
    public function decrypt($data, $password, $iv)
    {
        return $this->decryptAES($data, $password, $iv);

    }

    /**
     * @param $content
     * @param $key
     * @param $iv
     * @return string
     */
    function decryptAES($content, $key, $iv)
    {

        // Setzt den Verschlüsselungsalgorithmus
        // und setzt den Output Feedback (OFB) Modus
        $cp = mcrypt_module_open('rijndael-256', '', 'ofb', '');

        // Ermittelt die Anzahl der Bits, welche die Schlüssellänge des Keys festlegen
        $ks = mcrypt_enc_get_key_size($cp);

        // Erstellt den Schlüssel, der für die Verschlüsselung genutzt wird
        $key = substr(md5($key), 0, $ks);

        // Initialisiert die Verschlüsselung
        mcrypt_generic_init($cp, $key, $iv);

        // Entschlüsselt die Daten
        $decrypted = mdecrypt_generic($cp, $content);

        // Beendet die Verschlüsselung
        mcrypt_generic_deinit($cp);

        // Schließt das Modul
        mcrypt_module_close($cp);

        return trim($decrypted);
    }
}