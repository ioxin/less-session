<?php
namespace Less\Session\Container;

use Interop\Container\ContainerInterface;

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