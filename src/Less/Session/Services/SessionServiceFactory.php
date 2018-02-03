<?php
namespace Less\Session\Services;

use Interop\Container\ContainerInterface;
use Less\Session\Strategys\Cryptography\OpenSSL\OpenSslDecryptionStrategy;
use Less\Session\Strategys\Cryptography\OpenSSL\OpenSslEncryptionStrategy;

/**
 * Class SessionServiceFactory
 * @package Less\Session\Services
 */
class SessionServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return SessionService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new SessionService();

        //TODO: replace with injecten by config that gets its strategy by service manager
        $service->setDecryptionStrategy(new OpenSslDecryptionStrategy());
        $service->setEncryptionStrategy(new OpenSslEncryptionStrategy());

        return $service;
    }
}