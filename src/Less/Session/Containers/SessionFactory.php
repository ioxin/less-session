<?php
namespace Less\Session\Containers;

use Interop\Container\ContainerInterface;

/**
 * Class SessionFactory
 * @package Less\Session\Containers
 */
class SessionFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Session
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Session();
    }
}