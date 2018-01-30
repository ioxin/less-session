<?php
/**
 * Created by PhpStorm.
 * User: deb
 * Date: 24.01.18
 * Time: 13:57
 */

namespace Less\Session\Service;


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