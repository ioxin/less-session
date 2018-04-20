<?php
namespace Less\Session\Services;

use Interop\Container\ContainerInterface;
use Less\Session\Containers\Session;
use Less\Session\Strategys\Cryptography\OpenSSL\OpenSslDecryptionStrategy;
use Less\Session\Strategys\Cryptography\OpenSSL\OpenSslEncryptionStrategy;
use Zend\Session\Container;

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
        $session = $container->get(Session::class);
        $service = new SessionService(
            $session,
            new OpenSslEncryptionStrategy(),
            new OpenSslDecryptionStrategy()
        );

        return $service;
    }
}